<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Wasandev\InputThaiAddress\InputSubDistrict;
use Wasandev\InputThaiAddress\InputDistrict;
use Wasandev\InputThaiAddress\InputProvince;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\HasMany;
use Jfeid\NovaGoogleMaps\NovaGoogleMaps;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Number;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;



class Branch_area extends Resource
{
    //public static $displayInNavigation = false;
    public static $group = '5.งานจัดการการขนส่ง';
    public static $priority = 1;
    public static $globallySearchable = false;
    public static $preventFormAbandonment = true;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Branch_area';
    public static $with = ['branch'];
    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'district';
    public static function availableForNavigation(Request $request)
    {
        return $request->user()->hasPermissionTo('edit brancheareas');
    }
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'branch_id',  'district', 'province'
    ];
    public static function label()
    {
        return 'ข้อมูลพื้นที่บริการสาขา';
    }
    public static function singulatLabel()
    {
        return 'พื้นที่บริการสาขา';
    }

    public static $searchRelations = [
        'branch' => ['name'],
    ];
    public static $globalSearchRelations = [
        'branch' => ['name'],
    ];
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->hideFromIndex(),
            BelongsTo::make(__('Branch'), 'branch', 'App\Nova\Branch')->sortable(),

            InputDistrict::make(__('District'), 'district')
                ->withValues(['subdistrict', 'district', 'province', 'zipcode'])
                ->fromValue('amphoe')
                ->sortable()
                ->rules('required'),
            InputProvince::make(__('Province'), 'province')
                ->withValues(['subdistrict', 'district', 'province', 'zipcode'])
                ->fromValue('province')
                ->sortable()
                ->rules('required'),
            Number::make(__('Delivery days'), 'deliverydays')
                ->step('0.01'),
            //HasMany::make(__('Charter routes'), 'charter_routes', 'App\Nova\Charter_route'),
            NovaGoogleMaps::make(__('Google Map Address'), 'location')->setValue($this->location_lat, $this->location_lng)
                ->hideFromIndex(),
            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY hh:mm')
                ->onlyOnDetail(),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Updated At'), 'updated_at')
                ->format('DD/MM/YYYY hh:mm')
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
            new Filters\Branch,
            new Filters\Province,
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
            (new Actions\SetDeliverydays)
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit branchareas');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit branchareas');
                }),
            (new DownloadExcel)->allFields()->withHeadings()
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),
        ];
    }
    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return '/resources/' . static::uriKey();
    }

    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/' . static::uriKey();
    }
}
