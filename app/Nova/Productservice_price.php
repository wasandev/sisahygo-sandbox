<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Http\Requests\NovaRequest;
//use Wasandev\InputThaiAddress\InputSubDistrict;
use Wasandev\InputThaiAddress\InputDistrict;
use Wasandev\InputThaiAddress\InputProvince;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use OptimistDigital\MultiselectField\Multiselect;

class Productservice_price extends Resource
{
    //public static $displayInNavigation = false;
    public static $group = "4.งานด้านการตลาด";
    public static $priority = 8;

    public static $with = ['product', 'unit'];
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Productservice_price';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    //public static $title = 'id';

    public function title()
    {
        if (isset($this->product) && isset($this->unit)) {
            return $this->product->name . '=>' . number_format($this->price, 2, '.', ',') . '/' . $this->unit->name;
        } else {
            return $this->id;
        }
    }

    public function subtitle()
    {
        return  $this->district . ' ' . $this->province;
    }
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'district', 'province'
    ];
    public static $searchRelations = [
        'product' => ['name'],
    ];
    // public static $globalSearchRelations = [
    //     'product' => ['name'],
    // ];
    public static function label()
    {
        return __('Shipping costs');
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
            ID::make()->sortable()->hideFromIndex(),

            BelongsTo::make(__('Product'), 'product', 'App\Nova\Product')
                ->sortable()
                ->searchable(),
            BelongsTo::make(__('From branch'), 'from_branch', 'App\Nova\Branch')
                ->sortable(),

            // InputSubDistrict::make(__('Sub District'), 'sub_district')
            //     ->withValues(['district', 'amphoe', 'province', 'zipcode'])
            //     ->fromValue('district')
            //     ->rules('required'),
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
                ->sortable(),
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
            new Filters\ProductGroup,
            new Filters\ProductPriceStyle,
            new Filters\Product,
            new Filters\FromBranch,
            new Filters\Province,
            new Filters\ToDistrict,
            new Filters\Unit,

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
            new Actions\UpdateProductServicePrice,
            new Actions\UpdateProductServiceUnit,
            (new DownloadExcel)->allFields()->withHeadings(),

        ];
    }
    public static function relatableQuery(NovaRequest $request, $query)
    {
        if (isset($request->viaResourceId) && $request->viaRelationship === 'order_details') {

            $resourceId = $request->viaResourceId;

            $order = \App\Models\Order_checker::find($resourceId);
            $district = $order->to_customer->district;
            return $query->where('district', '=', $district);
        }
    }
}
