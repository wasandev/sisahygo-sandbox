<?php

namespace App\Nova\Lenses\Branch;

use App\Nova\Actions\Accounts\PrintBranchBalanceSummary;
use App\Nova\Filters\Branch;
use App\Nova\Filters\BranchBalanceFilter;
use App\Nova\Filters\BranchbalanceFromDate;
use App\Nova\Filters\BranchbalanceToDate;
use App\Nova\Filters\BranchSummaryToDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class BranchBalanceReport extends Lens
{
    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query->select(self::columns())
                ->join('branches', 'branch_balances.branch_id', '=', 'branches.id')
                ->join('customers', 'branch_balances.customer_id', '=', 'customers.id')
                ->where('branch_balances.pay_amount', '=', 0)
                ->orderBy('branch_balances.branch_id', 'asc')
                ->groupBy(
                    'branch_balances.branch_id'
                )
        ));
    }
    /**
     * Get the columns that should be selected.
     *
     * @return array
     */
    protected static function columns()
    {
        return [
            'branch_balances.branch_id',
            DB::raw('sum(branch_balances.bal_amount) as branch_amount'),
            DB::raw('sum(branch_balances.discount_amount) as discount_amount'),
            DB::raw('sum(branch_balances.tax_amount) as tax_amount'),
            DB::raw('sum(branch_balances.pay_amount) as pay_amount'),

        ];
    }
    /**
     * Get the fields available to the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('สาขา', function () {
                return $this->branch->name;
            }),
            Currency::make('ยอดค้างชำระ', function () {
                return $this->branch_amount - ($this->pay_amount + $this->tax_amount + $this->discount_amount);
            }),
        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new BranchBalanceFilter(),
            new BranchBalanceToDate(),
        ];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            (new PrintBranchBalanceSummary($request->filters))
                ->standalone()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view branch_balance');
                }),
            (new DownloadExcel)->allFields()
                ->withHeadings()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view branch_balance');
                }),
        ];
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'branch-branch-balance-report';
    }

    public function name()
    {
        return 'รายงานลูกหนี้สาขาค้างชำระ';
    }
}
