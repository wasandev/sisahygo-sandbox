<?php

namespace App\Nova;

use App\Nova\Metrics\OrderBillPerDay;
use App\Nova\Metrics\OrderBranchPerDay;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;

class Ar_balance extends Resource
{
    public static $group = '9.งานการเงิน/บัญชี';
    public static $priority = 8;
    public static $polling = true;
    public static $pollingInterval = 90;
    public static $showPollingToggle = true;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Ar_balance::class;

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
        'customer' => ['name'],
    ];
    public static function label()
    {
        return 'รายการวางบิล';
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
            BelongsTo::make('ชื่อลูกหนี้', 'customer', 'App\Nova\Ar_customer')
                ->searchable()
                ->sortable(),
            BelongsTo::make('เลขที่ใบรับส่ง', 'order_header', 'App\Nova\Order_header')
                ->sortable(),
            Boolean::make('การชำระเงิน', 'status', function () {
                return $this->order_header->payment_status;
            })->sortable(),
            Currency::make('จำนวนเงิน', 'ar_amount'),
            BelongsTo::make('ใบเสร็จรับเงิน', 'receipt', 'App\Nova\Receipt')
                ->sortable(),
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
        return [
            new OrderBillPerDay(),
        ];
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
