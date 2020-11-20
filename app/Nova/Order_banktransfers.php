<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Http\Requests\NovaRequest;

class Order_banktransfers extends Resource
{
    public static $group = '8.งานการเงิน/บัญชี';
    public static $priority = 4;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order_banktransfer::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    //public static $title = 'order_header_id';

    public function title()
    {
        return $this->order_header->order_header_no;
    }
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'account_no'
    ];
    public static function label()
    {
        return 'ข้อมูลรายการโอนเงินค่าขนส่ง';
    }
    public static function singularLabel()
    {
        return 'รายการโอนเงินค่าขนส่ง';
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
            Boolean::make(__('Status'), 'status'),
            BelongsTo::make(__('Order header no'), 'order_header', 'App\Nova\Order_header'),
            BelongsTo::make(__('Account no'), 'bankaccount', 'App\Nova\Bankaccount'),
            Currency::make(__('Amount'), 'transfer_amount'),
            Text::make(__('Bank reference no'), 'reference'),
            Image::make('สลิปโอนเงิน', 'transferslip')
                ->hideFromIndex(),
            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->exceptOnForms(),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->onlyOnDetail(),
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
        return [
            (new Actions\ConfirmBanktransfer($request->resourceId))
                ->onlyOnDetail()
                ->confirmText('ต้องการยืนยันรายการโอนเงิน รายการนี้?')
                ->confirmButtonText('ยืนยัน')
                ->cancelButtonText("ไม่ยืนยัน")
                ->canRun(function ($request, $model) {
                    return true;
                }),
        ];
    }
}
