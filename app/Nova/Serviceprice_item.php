<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Select;
use App\Models\Serviceprice;
use Wasandev\InputThaiAddress\InputDistrict;
use Wasandev\InputThaiAddress\InputProvince;
use Laravel\Nova\Fields\DateTime;

class Serviceprice_item extends Resource
{
    public static $displayInNavigation = false;
    public static $group = "4.งานด้านการตลาด";
    public static $priority = 10;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Serviceprice_item';

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
        'id', 'district', 'province'
    ];

    public static function label()
    {
        return __('Parcels shipping prices');
    }
    public static function singularLabel()
    {
        return __('Parcels shipping price');
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
            BelongsTo::make('ชื่อราคา', 'serviceprice', 'App\Nova\Serviceprice')
                ->hideFromIndex()
                ->sortable(),
            BelongsTo::make('ชื่อพัสดุ', 'parcel', 'App\Nova\Parcel')
                ->rules('required')
                ->sortable(),
            BelongsTo::make(__('From branch'), 'from_branch', 'App\Nova\Branch')
                ->rules('required')
                ->sortable(),
            InputDistrict::make(__('To district'), 'district')
                ->withValues(['amphoe', 'province'])
                ->fromValue('amphoe')
                ->sortable()
                ->rules('required'),
            InputProvince::make(__('To province'), 'province')
                ->withValues(['amphoe', 'province'])
                ->fromValue('province')
                ->sortable()
                ->rules('required'),
            Currency::make(__('Shipping cost'), 'price')
                ->rules('required')
                ->sortable(),
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
        return [
            new Filters\FromBranch,
            new Filters\Province,
            new Filters\Parcel,
        ];
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
            new Actions\UpdateServicepriceItem,
        ];
    }
}
