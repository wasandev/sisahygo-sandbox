<?php

namespace App\Nova\Lenses;

use App\Nova\Filters\OrderdateFilter;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Date;

class OrderBillingCash extends Lens
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
                ->join('users', 'users.id', '=', 'order_headers.user_id')
                ->where('order_headers.order_status', '=', 'confirmed')
                ->where('order_headers.paymenttype', '=', 'H')
                ->orderBy('order_headers.order_header_date', 'desc')
                ->groupBy('users.id', 'users.name', 'order_headers.order_header_date')
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
            'users.name',
            'order_headers.order_header_date',
            DB::raw('sum(order_headers.order_amount) as cash'),
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
            Text::make(__('Name'), 'name'),
            Date::make(__('Order date'), 'order_header_date')
                ->format('DD/MM/YYYY'),
            Currency::make(__('จำนวนเงิน'), 'cash', function ($value) {
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
            new OrderdateFilter()
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
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'order-billing-cash';
    }
    public function name()
    {
        return 'รายงานรับเงินสดตามพนักงาน';
    }
}
