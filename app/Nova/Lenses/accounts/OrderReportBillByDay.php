<?php

namespace App\Nova\Lenses\accounts;


use App\Nova\Actions\Accounts\PrintOrderReportBillByDay;
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

class OrderReportBillByDay extends Lens
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
                ->whereNotIn('order_headers.order_status', ['checking', 'new'])
                ->orderBy('branches.id', 'asc')
                ->orderBy('order_headers.order_header_date', 'asc')
                ->groupBy('branches.id', 'order_headers.order_header_date')
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
            'branches.id',
            'branches.name',
            'order_headers.order_header_date',
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

            Text::make('สาขาต้นทาง', 'name'),
            Date::make('วันที่', 'order_header_date'),
            Currency::make(__('จำนวนเงิน'), 'amount'),
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

            (new PrintOrderReportBillByDay($request->filters))
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
        return 'accounts-order-report-bill-by-day';
    }
    public function name()
    {
        return 'รายงานรายการขนส่งประจำวัน';
    }
}
