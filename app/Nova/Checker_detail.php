<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
//use Laravel\Nova\Fields\BelongsToMany;
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;

class Checker_detail extends Resource
{
    use HasDependencies;
    public static $displayInNavigation = false;
    public static $group = '7.งานบริการขนส่ง';
    public static $priority = 2;
    public static $globallySearchable = false;
    public static $preventFormAbandonment = true;
    public static $with = ['productservice_price'];

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Checker_detail::class;


    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return  $this->order_checker->order_header_no;
    }
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
        return __('Order details');
    }
    public static function singularLabel()
    {
        return __('Order detail');
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
            ID::make(__('ID'), 'id')->sortable()->onlyOnDetail(),
            //BelongsTo::make(__('Order header no'), 'order_header', 'App\Nova\Order_header'),
            BelongsTo::make(__('Product'), 'product', 'App\Nova\Product')
                ->exceptOnForms(),
            BelongsTo::make(__('Unit'), 'unit', 'App\Nova\Unit')
                ->exceptOnForms(),
            Currency::make('ค่าขนส่ง/หน่วย', 'price')
                ->exceptOnForms(),
            Boolean::make('ใช้ราคาจากตาราง', 'usepricetable')
                ->withMeta(["value" => 1])
                ->onlyOnForms(),

            // NovaDependencyContainer::make([
            BelongsTo::make(__('เลือกตารางราคา'), 'productservice_price', 'App\Nova\Productservice_price')
                ->searchable()
                ->withSubtitles()
                ->nullable()
                ->onlyOnForms(),
            //])->dependsOn('usepricetable', true),
            NovaDependencyContainer::make([
                BelongsTo::make(__('Product'), 'product', 'App\Nova\Product')
                    ->searchable()
                    ->rules('required'),
                BelongsTo::make(__('Unit'), 'unit', 'App\Nova\Unit')
                    ->searchable()
                    ->rules('required'),
                Currency::make('ค่าขนส่ง/หน่วย', 'price')
                    ->rules('required'),
            ])->dependsOn('usepricetable', false),
            Number::make('จำนวน', 'amount')
                ->step('0.01')
                ->rules('required'),

            Currency::make('จำนวนเงิน', function () {
                if ($this->usepricetable) {
                    return number_format($this->amount *  $this->productservice_price->price, 2, '.', ',');
                }
                return  number_format($this->amount *  $this->price, 2, '.', ',');
            }),
            number::make('น้ำหนักสินค้ารวม', 'order_weight', function () {
                return $this->amount * $this->weight;
            })->onlyOnIndex()
                ->step('0.01'),
            Number::make('น้ำหนักสินค้า/หน่วย(กก.)', 'weight')
                ->step('0.01')
                //->rules('required')
                ->help('สินค้าที่มีหน่วย กิโลกรัม ให้ใส่ 1')
                ->hideFromIndex()
                ->default(0.00),
            Text::make('หมายเหตุ', 'remark')
                ->nullable(),
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



    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return '/resources/' . $request->input('viaResource') . '/' . $request->input('viaResourceId');
    }

    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/' . $request->input('viaResource') . '/' . $request->input('viaResourceId');
    }
}
