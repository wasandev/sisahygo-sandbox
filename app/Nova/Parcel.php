<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;



class Parcel extends Resource
{
    public static $displayInNavigation = false;
    public static $group = "4.งานด้านการตลาด";
    public static $priority = 8;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Parcel';

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
        'name',
    ];

    public static function label()
    {
        return 'ข้อมูลพัสดุ';
    }
    public static function singularLabel()
    {
        return 'พัสดุ';
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
            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required'),

            Number::make(__('Width'), 'width')
                ->step('0.01')
                ->sortable()
                ->rules('required'),
            Number::make(__('Length'), 'length')
                ->step('0.01')
                ->sortable()
                ->rules('required'),
            Number::make(__('Height'), 'height')
                ->step('0.01')
                ->sortable()
                ->rules('required'),
            Text::make(__('Size'), function () {
                return $this->width + $this->length + $this->height;
            }),
            Number::make(__('Weight'), 'weight')
                ->step('0.01')
                ->sortable(),

            HasMany::make(__('Shipping cost'), 'serviceprice_items', 'App\Nova\Serviceprice_item'),

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
            (new DownloadExcel)->allFields()->withHeadings(),
        ];
    }
}
