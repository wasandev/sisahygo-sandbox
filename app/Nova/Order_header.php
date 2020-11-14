<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class Order_header extends Resource
{
    public static $group = '8.งานบริการขนส่ง';
    public static $priority = 1;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order_header::class;


    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'order_header_no';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'order_header_no'
    ];

    public static $searchRelations = [
        'customer' => ['name'],
        'to_customer' => ['name']
    ];
    public static $globalSearchRelations = [
        'customer' => ['name'],
        'to_customer' => ['name']
    ];

    public static function label()
    {
        return 'ข้อมูลใบรับส่งสินค้า';
    }
    public static function singularLabel()
    {
        return 'ใบรับส่งสินค้า';
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
            //ID::make(__('ID'), 'id')->sortable(),
            Status::make(__('Status'), 'order_status')
                ->loadingWhen(['New'])
                ->failedWhen(['Cancel'])
                ->exceptOnForms(),
            //BelongsTo::make('ใบกำกับสินค้า','waybill_id','App\Nova\Waybill'),
            Text::make(__('Order header no'), 'order_header_no')
                ->readonly(),
            Date::make(__('Order date'), 'order_header_date')
                ->readonly()
                ->default(today())
                ->format('DD/MM/YYYY'),
            BelongsTo::make(__('From branch'), 'branch', 'App\Nova\Branch')
                ->hideWhenCreating(),
            BelongsTo::make(__('To branch'), 'to_branch', 'App\Nova\Branch')
                ->hideWhenCreating(),
            BelongsTo::make('ผู้ส่งสินค้า', 'customer', 'App\Nova\Customer')
                ->searchable()
                ->showCreateRelationButton(),
            BelongsTo::make('ผู้รับสินค้า', 'to_customer', 'App\Nova\Customer')
                ->searchable()
                ->showCreateRelationButton(),
            Select::make(__('Payment type'), 'paymenttype')->options([
                'H' => 'เงินสดต้นทาง',
                'E' => 'เงินสดปลายทาง',
                'T' => 'เงินโอน',
                'F' => 'วางบิลต้นทาง',
                'L' => 'วางบิลปลายทาง'
            ])->displayUsingLabels()
                ->hideFromIndex()
                ->default('H'),


            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Updated At'), 'updated_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            HasMany::make(__('Order detail'), 'order_details', 'App\Nova\Order_detail'),
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
            (new Actions\OrderConfirmed)
                ->onlyOnDetail()
                ->confirmText('ต้องการยืนยันใบรับส่งรายการนี้?')
                ->confirmButtonText('ยืนยัน')
                ->cancelButtonText("ไม่ยืนยัน")
                ->canRun(function ($request, $model) {
                    return true;
                }),
        ];
    }
}
