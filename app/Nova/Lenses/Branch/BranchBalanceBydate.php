<?php

namespace App\Nova\Lenses\Branch;

use App\Nova\Actions\Accounts\PrintBranchBalanceByDate;
use App\Nova\Filters\Branch;
use App\Nova\Filters\BranchBalanceFilter;
use App\Nova\Filters\BranchbalanceFromDate;
use App\Nova\Filters\BranchbalanceToDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class BranchBalanceBydate extends Lens
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
                ->orderBy('branch_balances.branch_id', 'asc')
                ->orderBy('branch_balances.branchbal_date', 'asc')
                ->groupBy(
                    'branch_balances.branch_id',
                    'branch_balances.branchbal_date'
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
            'branch_balances.branchbal_date',
            DB::raw('sum(branch_balances.bal_amount) as branch_amount'),
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
            Date::make('วันที่', 'branchbal_date'),

            Currency::make('จำนวนเงิน', 'branch_amount'),
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
            new BranchbalanceFromDate(),
            new BranchbalanceToDate(),
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
            (new PrintBranchBalanceByDate($request->filters))
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
        return 'branch-branch-balance-bydate';
    }

    public function name()
    {
        return 'รายงานตั้งหนี้ลูกหนี้สาขา';
    }
}
