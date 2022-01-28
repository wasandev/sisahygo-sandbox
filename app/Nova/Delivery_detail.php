<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Delivery_detail extends Resource
{
    public static $displayInNavigation = false;
    public static $group = '8.สำหรับสาขา';
    public static $priority = 4;

    public static $globallySearchable = false;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Delivery_detail::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];
    public static $searchRelations = [
        'branchrec_order' => ['order_header_no', 'order_header_id'],
    ];
    public static function label()
    {
        return 'รายการใบรับส่งในใบจัดส่ง';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make('ใบรับส่ง', 'branchrec_order', 'App\Nova\Branchrec_order'),
            Boolean::make('สถานะการจัดส่ง', 'delivery_status')
                ->exceptOnForms(),
            Text::make('ประเภทการชำระเงิน', 'payment_type', function () {
                $order = \App\Models\Branchrec_order::find($this->order_header_id);
                return $order->paymenttype;
            }),
            Boolean::make('สถานะการเก็บเงิน', 'payment_status', function ($request) {
                $order = \App\Models\Branchrec_order::find($this->order_header_id);
                if ($order->paymenttype == 'H' || $this->payment_status) {
                    return true;
                } else {
                    return false;
                }
            })->exceptOnForms(),

            Number::make('จำนวนเงิน', 'payamount', function () {
                $order = \App\Models\Branchrec_order::find($this->order_header_id);
                return $order->order_amount;
            })->step('0.01'),
            HasMany::make('รายการสินค้า', 'order_details', 'App\Nova\Order_detail'),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
