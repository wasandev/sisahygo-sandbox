<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Wasandev\InputThaiAddress\InputDistrict;
use Wasandev\InputThaiAddress\InputProvince;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;



class Customer_product_price extends Resource
{
    public static $displayInNavigation = false;
    public static $group = "4.งานด้านการตลาด";
    public static $priority = 8;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Customer_product_price';

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
        return __('Customer shipping cost');
    }
    public static function singularLabel()
    {
        return __('Shipping cost');
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
            Boolean::make(__('Status'), 'active')
                ->sortable()
                ->rules('required'),
            BelongsTo::make(__('Customer'), 'customer', 'App\Nova\Customer')
                ->sortable()
                ->rules('required'),
            BelongsTo::make(__('Product'), 'product', 'App\Nova\Product')
                ->sortable()
                ->rules('required'),

            BelongsTo::make(__('From branch'), 'from_branch', 'App\Nova\Branch')
                ->sortable()
                ->rules('required'),
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
            BelongsTo::make(__('Unit'), 'unit', 'App\Nova\Unit')
                ->sortable()
                ->rules('required'),
            Currency::make(__('Shipping cost'), 'price')
                ->sortable()
                ->rules('required'),
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
            new Filters\Customer,
            new Filters\FromBranch,
            new Filters\Province,
            new Filters\Product,
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
            new Actions\SetCustomerProductPriceActive,
            new Actions\UpdateProductServicePrice,
            new Actions\UpdateProductServiceUnit,
            (new DownloadExcel)->withWriterType(\Maatwebsite\Excel\Excel::XLSX)->withHeadings(),
        ];
    }
}
