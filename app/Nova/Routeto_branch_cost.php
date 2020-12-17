<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;

class Routeto_branch_cost extends Resource
{
    public static $displayInNavigation = false;
    public static $group = "5.งานจัดการการขนส่ง";
    public static $priority = 2;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Routeto_branch_cost';

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
        return __('Shipping cost data');
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
            ID::make()->sortable(),
            Boolean::make(__('Status'), 'status')
                ->sortable()
                ->rules('required'),
            BelongsTo::make(__('Route to branch'), 'routeto_branch', 'App\Nova\Routeto_branch')
                ->sortable()
                ->rules('required'),
            BelongsTo::make(__('Car type'), 'cartype', 'App\Nova\Cartype')
                ->sortable()
                ->rules('required'),
            BelongsTo::make(__('Car style'), 'carstyle', 'App\Nova\Carstyle')
                ->sortable()
                ->nullable(),

            Currency::make(__('Full truck rate'), 'fulltruckrate')
                ->sortable()
                ->nullable(),
            Currency::make('ค่าจ้างรถ(กรณีรถร่วม)', 'car_charge')
                ->sortable()
                ->nullable(),
            Currency::make('ค่าเที่ยวคนขับ(กรณีรถบริษัท)', 'driver_charge')
                ->sortable()
                ->nullable(),
            Currency::make('ค่าเชื้อเพลิงที่ใช้(บาท)', 'fuel_cost')
                ->sortable()
                ->nullable(),
            Number::make('จำนวนเชื้อเพลิงที่ใช้(ลิตร)', 'fuel_amount')
                ->step('0.01')
                ->sortable()
                ->nullable()
                ->hideFromIndex(),

            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->OnlyOnDetail(),
            DateTime::make(__('Updated At'), 'updated_at')
                ->format('DD/MM/YYYY HH:mm')
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
        return [];
    }
}
