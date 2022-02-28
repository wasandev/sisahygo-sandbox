<?php

namespace App\Nova;

use App\Nova\Metrics\ServicePriceUpdate;
use App\Nova\Metrics\UpdatePricePerDay;
use Carbon\Carbon;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;
use Wasandev\InputThaiAddress\InputDistrict;
use Wasandev\InputThaiAddress\InputProvince;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Suenerds\NovaSearchableBelongsToFilter\NovaSearchableBelongsToFilter;

class Productservice_newprice extends Resource
{
    public static $displayInNavigation = false;
    public static $group = "4.งานด้านการตลาด";
    public static $priority = 8.1;
    public static $perPageOptions = [50, 100, 150];
    public static $perPageViaRelationship = 50;
    public static $relatableSearchResults = 200;
    public $refreshWhenActionRuns = true;

    public static $with = ['product', 'unit', 'branch_area'];
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Productservice_newprice';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    //public static $title = 'id';

    public function title()
    {
        if (isset($this->product) && isset($this->unit)) {
            return $this->product->name . "-" . number_format($this->price, 2, '.', ',') . '/' . $this->unit->name;
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
        'id', 'district', 'province', 'price'
    ];
    public static $searchRelations = [
        'product' => ['name', 'id'],
    ];

    public static function label()
    {
        return 'ปรับราคาค่าขนส่ง';
    }
    public static function singularLabel()
    {
        return 'ปรับราคาค่าขนส่ง';
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
            //ID::make()->sortable(),
            Boolean::make('สถานะการปรับ', function () {
                return ($this->updated_at->month == 2);
            }),
            DateTime::make(__('Updated At'), 'updated_at')
                ->format('DD/MM/YYYY HH:mm')
                ->exceptOnForms()
                ->sortable(),

            BelongsTo::make(__('Product'), 'product', 'App\Nova\Product')
                ->sortable()
                ->searchable(),
            BelongsTo::make(__('From branch'), 'from_branch', 'App\Nova\Branch')
                ->sortable()
                ->hideFromIndex(),
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
            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->OnlyOnDetail(),

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
        return [
            (new ServicePriceUpdate())->help('**เริ่มนับรายการจากวันที่ 15/2/2022')->width('full'),
            //new UpdatePricePerDay()
        ];
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
            (new NovaSearchableBelongsToFilter('ตามสินค้า'))
                ->fieldAttribute('product')
                ->filterBy('product_id'),
            (new Filters\ToDistrict),
            (new Filters\Province),
            //(new Filters\Product),
            (new Filters\ProductPriceStyle),
            (new Filters\ProductGroup),
            (new Filters\Unit),

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
            (new Actions\UpdateProductServicePrice)
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit productservice_prices');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit productservice_prices');
                }),
            (new Actions\UpdateProductServiceUnit)
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit productservice_prices');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit productservice_prices');
                }),
            (new DownloadExcel)->allFields()->withHeadings()
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit productservice_prices');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit productservice_prices');
                }),

        ];
    }
    public static function relatableQuery(NovaRequest $request, $query)
    {
        if (isset($request->viaResourceId) && ($request->viaRelationship === 'order_details' || $request->viaRelationship === 'checker_details')) {

            $resourceId = $request->viaResourceId;
            // $tableprice = \App\Models\Tableprice::where('status', true)->first();

            $order = \App\Models\Order_checker::find($resourceId);
            if ($order->branch->code === '001' || $order->branch->dropship_flag) {
                $district = $order->to_customer->district;
            } else {
                $district = $order->customer->district;
            }
            return $query->where('district', '=', $district)
                ->where('price', '>', 0);
        }
    }
}
