<?php

namespace App\Nova\Lenses\accounts;

use App\Nova\Actions\Accounts\PrintOrderReportByDay;
use App\Nova\Filters\Branch;
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

class OrderReportByDay extends Lens
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
                ->whereNotIn('order_headers.order_status', ['checking', 'new', 'cancel'])
                ->orderBy('order_headers.branch_id', 'asc')
                ->orderBy('order_headers.order_header_date')
                ->orderBy('order_headers.order_type')
                ->groupBy('order_headers.branch_id', 'order_headers.order_header_date', 'order_headers.order_type')
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
            'order_headers.order_header_date',
            'order_headers.order_type',
            DB::raw('sum(order_headers.order_amount) as amount'),
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

            Text::make(__('Branch'), 'name'),
            Date::make('วันที่', 'order_header_date'),
            Text::make('ประเภท', function () {
                if ($this->order_type == 'general') {
                    $order_type = 'ทั่วไป';
                } elseif ($this->order_type == 'express') {
                    $order_type = 'Express';
                } else {
                    $order_type = 'เหมาคัน';
                }
                return $order_type;
            }),
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
            new Branch(),
            new OrderFromDate(),
            new OrderToDate()
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
            (new PrintOrderReportByDay($request->filters))
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
        return 'accounts-order-report-by-day';
    }
    public function name()
    {
        return 'รายงานยอดค่าขนส่งตามวัน';
    }
}
