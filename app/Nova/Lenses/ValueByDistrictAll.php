<?php

namespace App\Nova\Lenses;

use App\Nova\Filters\OrderdateFilter;
use App\Nova\Filters\OrderFromDate;
use App\Nova\Filters\OrderToBranch;
use App\Nova\Filters\OrderToDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class ValueByDistrictAll extends Lens
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
                ->join('customers', 'customers.id', '=', 'order_headers.customer_rec_id')
                ->join('branches', 'branches.id', '=', 'order_headers.branch_rec_id')
                ->whereNotIn('order_headers.order_status', ['checking', 'new', 'problem', 'cancel'])
                ->where('order_headers.order_type', '<>', 'charter')
                ->orderBy('order_headers.branch_rec_id', 'desc')
                ->orderBy('amount', 'desc')
                ->groupBy('order_headers.branch_rec_id', 'customers.district')
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
            'customers.district',
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
            // ID::make(__('ID'), 'id')->sortable(),
            Text::make(__('Branch'), 'name'),
            Text::make(__('District'), 'district'),
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
            new OrderToBranch(),
            new OrderFromDate(),
            new OrderToDate(),
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
        return 'value-district-amount-all';
    }
    public function name()
    {
        return 'ยอดค่าขนส่งทั่วไปตามอำเภอ';
    }
}
