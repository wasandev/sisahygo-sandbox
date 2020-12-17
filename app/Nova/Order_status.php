<?php

namespace App\Nova;


use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Order_status extends Resource
{
    public static $displayInNavigation = false;
    public static $group = '8.งานบริการขนส่ง';
    public static $globallySearchable = false;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order_status::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'order_header_id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id'
    ];


    public static function label()
    {
        return 'ข้อมูลสถานะใบรับส่งสินค้า';
    }
    public static function singularLabel()
    {
        return 'สถานะใบรับส่งสินค้า';
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
            // ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make(__('Order header no'), 'order_header', 'App\Nova\Order_header')
                ->hideFromIndex(),
            DateTime::make(__('Status time'), 'created_at')
                ->format('DD/MM/YYYY HH:mm'),
            Text::make(__('Order status'), function ($status) {
                if ($this->status == 'confirmed') {
                    $status = $this->order_header->branch->name . '-' . 'รับสินค้าไว้แล้ว';
                } elseif ($this->status == 'loaded') {
                    $status = 'จัดสินค้าขึ้นรถแล้ว' . '- ทะเบียน' . ' ' . $this->order_header->waybill->car->car_regist;
                } elseif ($this->status == 'in transit') {
                    $status = 'สินค้าอยู่ระหว่างขนส่งไปสาขา' . '-' . $this->order_header->to_branch->name;
                } elseif ($this->status == 'arrival') {
                    $status = 'สินค้าถึงสาขาปลายทาง';
                } elseif ($this->status == 'branch warehouse') {
                    $status = 'สินค้าอยู่คลังสาขา รอการจัดส่ง';
                } elseif ($this->status == 'delivery') {
                    $status = 'สินค้าอยู่ระหว่างการจัดส่ง';
                } elseif ($this->status == 'completed') {
                    $status = 'สินค้าจัดส่งถึงผู้รับปลายทางแล้ว';
                } elseif ($this->status == 'problem') {
                    $status = 'มีปัญหาการขนส่ง';
                } elseif ($this->status == 'cancel') {
                    $status = 'ยกเลิกรายการแล้ว';
                }

                return $status;
            }),
            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User'),
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
}
