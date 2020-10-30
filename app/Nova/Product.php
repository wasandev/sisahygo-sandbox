<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;




class Product extends Resource
{
    //public static $displayInNavigation = false;
    public static $group = "4.งานด้านการขาย";
    public static $priority = 6;

    //public static $displayInNavigation = false;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Product';

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
        'name'
    ];
    public static function label()
    {
        return __('Products');
    }
    public static function singularLabel()
    {
        return __('Product');
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
                ->sortable(),
            BelongsTo::make(__('Category'), 'category', 'App\Nova\Category')
                ->sortable()
                ->showCreateRelationButton(),
            BelongsTo::make(__('Product style'), 'product_style', 'App\Nova\Product_style')
                ->sortable()
                ->showCreateRelationButton(),
            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required'),
            Number::make(__('Width'), 'width')
                ->step('0.01')
                ->hideFromIndex(),
            Number::make(__('Length'), 'length')
                ->step('0.01')
                ->hideFromIndex(),
            Number::make(__('Height'), 'height')
                ->step('0.01')
                ->hideFromIndex(),
            Number::make(__('Weight'), 'weight')
                ->step('0.01')
                ->hideFromIndex(),
            BelongsTo::make(__('Unit'), 'unit', 'App\Nova\Unit')
                ->showCreateRelationButton(),
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
            HasMany::make(__('Shipping costs'), 'productservice_price', 'App\Nova\Productservice_price'),
            // BelongsToMany::make('ลูกค้าที่ใช้สินค้านี้', 'customer', 'App\Nova\Customer'),
            // HasMany::make('ค่าขนส่งสินค้าตามลูกค้า', 'customer_product_prices', 'App\Nova\Customer_product_price')

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
            (new Actions\AddProductServicePrice),
            //new Actions\AddProductCustomerPrice,
            new DownloadExcel,


        ];
    }
}
