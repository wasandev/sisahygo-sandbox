<?php

namespace App\Nova;

use Laravel\Nova\Fields\DateTime;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Delivery_item extends Resource
{
    public static $displayInNavigation = false;
    public static $group = '8.สำหรับสาขา';
    //public static $priority = 3;
    // public static $polling = true;
    // public static $pollingInterval = 90;
    // public static $showPollingToggle = true;
    public static $globallySearchable = false;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Delivery_item::class;

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

    public static function label()
    {
        return 'รายการใบรับส่ง';
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
            BelongsTo::make('เลขที่รายการจัดส่ง', 'delivery', 'App\Nova\Delivery'),
            BelongsTo::make(__('Order header no'), 'branchrec_order', 'App\Nova\Branchrec_order'),
            Text::make('ประเภทการชำระเงิน', 'paymenttype', function ($request) {
                $order = \App\Models\Branchrec_order::find($this->order_header_id);
                return $order->paymenttype;
            })->onlyOnIndex(),

            Text::make('จำนวนเงินจัดเก็บ', 'branchrec', function ($request) {

                $order = \App\Models\Branchrec_order::find($this->order_header_id);

                if ($order->paymenttype === 'E') {
                    return $order->order_amount;
                }
                return '-';
            })->onlyOnIndex(),

            Boolean::make('สถานะการจัดส่ง', 'delivery_status')
                ->exceptOnForms(),
            Boolean::make('สถานะการเก็บเงิน', 'payment_status')
                // ->canSee(function ($request) {
                //     $order = \App\Models\Branchrec_order::find($this->order_header_id);
                //     return $order->paymenttype == 'E';
                // })
                ->onlyOnIndex(),
            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Updated At'), 'updated_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail()
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

    public static function relatableBranchrec_orders(NovaRequest $request, $query)
    {
        // if ($request->viaResourceId && $request->viaRelationship == 'delivery_items') {

        //     $resourceId = $request->viaResourceId;

        //     $order = \App\Models\Order_checker::find($resourceId);
        //     $district = $order->to_customer->district;
        //     //dd($district);
        //     return $query->orWhere('district', $district);
        // }
        return $query->whereIn('order_status', ['arrival', 'branch warehouse'])
            ->where('branch_rec_id', '=', $request->user()->branch_id);
    }

    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return '/resources/' . $request->input('viaResource') . '/' . $request->input('viaResourceId');
    }

    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/' . $request->input('viaResource') . '/' . $request->input('viaResourceId');
    }
}
