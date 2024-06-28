<?php

namespace App\Nova\Lenses\accounts;


use App\Nova\Actions\Accounts\PrintOrderReportCrByDay;
use App\Nova\Filters\Accounts\ArType;
use App\Nova\Filters\Branch;
use App\Nova\Filters\Accounts\CancelFlag;
use App\Nova\Filters\OrderFromDate;
use App\Nova\Filters\OrderToDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class OrderReportCrByDay extends Lens
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
                ->join('branches', 'branches.id', '=', 'order_headers.branch_id')
                ->join('customers as a', 'a.id', '=', 'order_headers.customer_id')
                ->join('customers as b', 'b.id', '=', 'order_headers.customer_rec_id')
                ->whereIn('order_headers.paymenttype', ['E', 'F', 'L'])
                ->whereNotIn('order_headers.order_status', ['checking', 'new', 'cancel'])
                ->orderBy('order_headers.branch_id', 'asc')
                ->orderBy('order_headers.order_header_no', 'asc')

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
            'branches.name',
            'order_headers.id',
            'order_headers.order_header_date',
            'order_headers.order_header_no',
            'a.name as from_customer',
            'b.name as to_customer',
            'order_headers.order_amount',
            'order_headers.paymenttype',
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
            ID::make(__('ID'), 'id')->sortable(),
            Text::make(__('Branch'), 'name'),
            Date::make('วันที่', 'order_header_date'),
            Text::make('เลขที่ใบรับส่ง', 'order_header_no'),
            Text::make('ชื่อลูกค้า', function () {
                if ($this->paymenttype == 'L' || $this->paymenttype == 'E') {
                    return $this->to_customer;
                } else {
                    return $this->from_customer;
                }
            }),
            Text::make('ประเภท', function () {
                if ($this->paymenttype == 'F' || $this->paymenttype == 'L') {
                    return 'วางบิล';
                } else {
                    return 'เก็บเงินปลายทาง';
                }
            }),
            Currency::make(__('จำนวนเงิน'), 'order_amount')
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
            new Branch(),
            new OrderFromDate(),
            new OrderToDate(),
            new ArType(),
            new CancelFlag()
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

            (new PrintOrderReportCrByDay($request->filters))
                ->standalone()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view order_headers');
                }),
            (new DownloadExcel)->allFields()->withHeadings()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view order_headers');
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
        return 'accounts-order-report-cr-by-day';
    }
    public function name()
    {
        return 'รายงานขายเชื่อประจำวัน';
    }
}
