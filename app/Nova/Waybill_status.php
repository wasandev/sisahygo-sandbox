<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;

use Laravel\Nova\Http\Requests\NovaRequest;

class Waybill_status extends Resource
{
    public static $displayInNavigation = false;
    public static $group = '8.งานบริการขนส่ง';
    public static $globallySearchable = false;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Waybill_status::class;

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
        return 'ข้อมูลสถานะใบกำกับสินค้า';
    }
    public static function singularLabel()
    {
        return 'สถานะใบกำกับสินค้า';
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
            ID::make(__('ID'), 'id')->hideFromIndex(),
            BelongsTo::make(__('Waybill'), 'waybill', 'App\Nova\Waybill')
                ->hideFromIndex(),
            DateTime::make(__('Status time'), 'created_at')
                ->format('DD/MM/YYYY HH:mm'),
            Text::make(__('Status'), function ($status) {
                if ($this->status == 'loading') {
                    $status = 'กำลังจัดสินค้าขึ้นรถบรรทุก';
                } elseif ($this->status == 'confirmed') {
                    $status = 'สินค้าเต็มคัน ออกใบกำกับแล้ว';
                } elseif ($this->status == 'in transit') {
                    $status = 'รถบรรทุกออกจากสาขาต้นทางแล้ว';
                } elseif ($this->status == 'arrival') {
                    $status = 'รถบรรทุกถึงสาขาปลายทางแล้ว';
                } elseif ($this->status == 'delivery') {
                    $status = 'อยู่ระหว่างการกระจายสินค้า';
                } elseif ($this->status == 'completed') {
                    $status = 'กระจายสินค้าหมดแล้ว';
                } elseif ($this->status == 'cancel') {
                    $status = 'ยกเลิกรายการ';
                } elseif ($this->status == 'ploblem') {
                    $status = 'มีปัญหาการขนส่ง';
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
