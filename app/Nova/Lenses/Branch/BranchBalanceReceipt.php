<?php

namespace App\Nova\Lenses\Branch;

use App\Nova\Actions\Accounts\PrintBranchBalanceReceipt;
use App\Nova\Filters\BranchPayFromDate;
use App\Nova\Filters\BranchPayToDate;
use App\Nova\Filters\BranchReceiptLenseFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class BranchBalanceReceipt extends Lens
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
                ->join('receipts', 'branch_balances.receipt_id', '=', 'receipts.id')
                ->where('branch_balances.payment_status', '=', true)
                ->orderBy('branch_balances.branch_id', 'asc')
                ->orderBy('branch_balances.branchpay_date', 'desc')
                ->groupBy(
                    'branch_balances.branch_id',
                    'branch_balances.branchpay_date',
                    'receipts.branchpay_by'
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
            'branch_balances.branchpay_date',
            'receipts.branchpay_by as payby',
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
            Date::make('วันที่', 'branchpay_date'),
            Text::make('ชำระด้วย', function () {
                if (isset($this->payby)) {
                    if ($this->payby == 'C') {
                        return 'เงินสด';
                    } else {
                        return 'เงินโอน';
                    }
                }
                return '';
            }),
            Currency::make('ค่าขนส่ง', function () {
                return  $this->branch_amount;
            }),
            Currency::make('ส่วนลด', function () {
                return  $this->discount_amount;
            }),
            Currency::make('ภาษี', function () {
                return  $this->tax_amount;
            }),
            Currency::make('ยอดรับชำระ', function () {
                return  $this->pay_amount;
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
            new BranchReceiptLenseFilter(),
            new BranchPayFromDate(),
            new BranchPayToDate(),
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
            (new PrintBranchBalanceReceipt($request->filters))
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
        return 'branch-branch-balance-receipt';
    }

    public function name()
    {
        return 'รายงานชำระหนี้ลูกหนี้สาขา';
    }
}
