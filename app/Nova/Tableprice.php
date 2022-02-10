<?php

namespace App\Nova;

use App\Nova\Actions\CopyProductPrice;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Tableprice extends Resource
{

    public static $displayInNavigation = false;
    public static $group = "4.งานด้านการตลาด";
    public static $priority = 8;
    public static $perPageOptions = [50, 100, 150];


    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Tableprice::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name'
    ];
    public static function label()
    {
        return 'ตารางราคา';
    }
    public static function singularLabel()
    {
        return 'ตารางราคา';
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
            Boolean::make('ใช้งาน', 'status'),
            Text::make('ชื่อ', 'name'),
            Date::make('วันที่เริ่มใช้งาน', 'start_date')->rules('required'),
            Date::make('วันที่สิ้นสุดการใช้งาน', 'end_date')->nullable(),

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
            HasMany::make('ราคาค่าขนส่ง', 'productservice_prices', 'App\Nova\Productservice_price'),
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
            (new CopyProductPrice())
                ->onlyOnDetail()
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit productservice_prices');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit productservice_prices');
                }),
        ];
    }
}
