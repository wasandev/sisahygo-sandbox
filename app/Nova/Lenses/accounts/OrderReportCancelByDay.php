<?php

namespace App\Nova\Lenses\accounts;


use App\Nova\Actions\Accounts\PrintOrderReportCancelByDay;
use App\Nova\Filters\Branch;
use App\Nova\Filters\Lenses\CancelFromDate;
use App\Nova\Filters\Lenses\CancelToDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class OrderReportCancelByDay extends Lens
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
                ->join('order_statuses', function ($join) {
                    $join->on('order_headers.id', '=', 'order_statuses.order_header_id')
                        ->where('order_statuses.status', '=', 'cancel');
                })
                ->where('order_headers.order_status', 'cancel')
                ->whereNotNull('order_header_no')
                ->orderBy('order_headers.branch_id', 'asc')
                ->orderBy('order_headers.order_header_date', 'asc')

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
            'order_headers.order_status',
            'order_statuses.user_id as canceled_by',
            'order_statuses.created_at as canceled_at'
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
            Text::make('ผู้ส่งสินค้า', 'from_customer'),
            Text::make('ผู้รับสินค้า', 'to_customer'),
            Currency::make(__('จำนวนเงิน'), 'order_amount'),
            //Text::make('สถานะ', 'order_status'),
            Text::make('ยกเลิกโดย', function () {
                $user = \App\Models\User::find($this->canceled_by);
                return $user->name;
            }),

            DateTime::make('วันที่ยกเลิก', 'canceled_at')

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
            new CancelFromDate(),
            new CancelToDate()
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

            (new PrintOrderReportCancelByDay($request->filters))
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
        return 'accounts-order-report-cancel-by-day';
    }
    public function name()
    {
        return 'รายงานรายการยกเลิกใบรับส่ง';
    }
}
