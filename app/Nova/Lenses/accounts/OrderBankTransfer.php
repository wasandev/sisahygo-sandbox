<?php

namespace App\Nova\Lenses\accounts;

use App\Nova\Actions\Accounts\PrintOrderBanktransfer;
use App\Nova\Actions\Accounts\PrintOrderBillingCash;
use App\Nova\Filters\BankTransferDateFilter;
use App\Nova\Filters\Branch;
use App\Nova\Filters\LensBranchFilter;
use App\Nova\Filters\OrderdateFilter;
use App\Nova\Filters\OrderFromDate;
use App\Nova\Filters\OrderToDate;
use App\Nova\Metrics\OrderCashPerDay;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Date;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class OrderBankTransfer extends Lens
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
                ->join('branches', 'branches.id', '=', 'order_banktransfers.branch_id')
                ->join('users', 'users.id', '=', 'order_banktransfers.user_id')
                ->join('order_headers', 'order_headers.id', '=', 'order_banktransfers.order_header_id')
                ->orderBy('order_banktransfers.id', 'asc')
                ->groupBy('order_banktransfers.transfer_type')
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
            'branches.name as branch_name',
            'order_banktransfers.transfer_type as transfer_type',
            DB::raw('sum(order_banktransfers.transfer_amount) as amount'),
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

            Text::make(__('Branch'), 'branch_name'),
            Text::make('ประเภทรายการ', 'transfer_type'),
            Currency::make(__('จำนวนเงิน'), 'amount', function ($value) {
                return $value;
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

            new BankTransferDateFilter()
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
            (new PrintOrderBanktransfer($request->filters))
                ->standalone()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view order_banktransfers');
                }),
            (new DownloadExcel)->allFields()
                ->withHeadings()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view order_banktransfers');
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
        return 'order-banktransfer';
    }
    public function name()
    {
        return 'รายงานรายการโอนเงินค่าขนส่ง';
    }
}
