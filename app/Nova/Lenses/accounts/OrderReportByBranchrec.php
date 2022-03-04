<?php

namespace App\Nova\Lenses\accounts;

use App\Nova\Actions\Accounts\PrintOrderReportByBranchrec;
use App\Nova\Filters\OrderFromDate;
use App\Nova\Filters\OrderToBranch;
use App\Nova\Filters\OrderToDate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class OrderReportByBranchrec extends Lens
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
                ->join('branches', 'branches.id', '=', 'order_headers.branch_rec_id')
                ->whereNotIn('order_headers.order_status', ['checking', 'new', 'cancel'])
                ->orderBy('order_year', 'asc')
                ->orderBy('order_month', 'asc')
                ->orderBy('order_headers.branch_rec_id', 'asc')
                ->groupBy('order_year', 'order_month', 'order_headers.branch_rec_id')

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
            DB::raw('YEAR(order_headers.order_header_date) order_year, MONTH(order_headers.order_header_date) order_month'),
            DB::raw("SUM(CASE WHEN order_headers.order_type = 'general' THEN order_headers.order_amount ELSE 0 END) as general_amount"),
            DB::raw("SUM(CASE WHEN order_headers.order_type = 'express' THEN order_headers.order_amount ELSE 0 END) as express_amount"),
            DB::raw("SUM(CASE WHEN order_headers.order_type = 'charter' THEN order_headers.order_amount ELSE 0 END) as charter_amount"),
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

            Text::make('ปี-เดือน', function () {
                return $this->order_year . '-' . $this->order_month;
            }),
            Text::make(__('Branch'), 'name'),
            Currency::make(__('ทั่วไป'), 'general_amount', function ($value) {
                return $value;
            }),
            Currency::make(__('เหมาคัน'), 'charter_amount', function ($value) {
                return $value;
            }),
            Currency::make(__('Express'), 'express_amount', function ($value) {
                return $value;
            }),
            Currency::make(__('รวม'), 'amount', function ($value) {
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
            new OrderToBranch(),
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
            (new PrintOrderReportByBranchrec($request->filters))
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
        return 'accounts-order-report-by-branchrec';
    }
    public function name()
    {
        return 'รายงานยอดค่าขนส่งตามสาขาปลายทาง';
    }
}
