<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
//use Laravel\Nova\Fields\BelongsToMany;
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Laravel\Nova\Fields\Boolean;

class Order_detail extends Resource
{
    use HasDependencies;
    public static $displayInNavigation = false;
    public static $group = '8.งานบริการขนส่ง';
    public static $priority = 2;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order_detail::class;


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
            //ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make(__('Order header no'), 'order_header', 'App\Nova\Order_header'),
            BelongsTo::make(__('Product'), 'product', 'App\Nova\Product')
                ->onlyOnIndex(),
            BelongsTo::make(__('Unit'), 'unit', 'App\Nova\Unit')
                ->onlyOnIndex(),
            Currency::make('ค่าขนส่ง/หน่วย', 'price')
                ->onlyOnIndex(),
            Boolean::make('ใช้ราคาจากตาราง', 'usepricetable')
                ->withMeta(["value" => 1])
                ->hideFromIndex(),

            NovaDependencyContainer::make([
                BelongsTo::make(__('เลือกตารางราคา'), 'productservice_price', 'App\Nova\Productservice_price')
                    ->searchable()
                    ->nullable()
                    ->showOnIndex(),
            ])->dependsOn('usepricetable', true),
            NovaDependencyContainer::make([
                BelongsTo::make(__('Product'), 'product', 'App\Nova\Product')
                    ->searchable()
                    ->nullable(),
                BelongsTo::make(__('Unit'), 'unit', 'App\Nova\Unit')
                    ->searchable()
                    ->nullable(),
                Currency::make('ค่าขนส่ง', 'price')
            ])->dependsOn('usepricetable', false),
            Number::make('จำนวน', 'amount')
                ->step('0.01'),

            Currency::make('จำนวนเงิน', function () {
                if ($this->usepricetable) {
                    return $this->amount *  $this->productservice_price->price;
                }
                return $this->amount *  $this->price;
            }),
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

    public static function relatableProductservice_prices(NovaRequest $request, $query)
    {

        $resourceId = $request->query('viaResourceId');
        if ($resourceId !== null) {

            $order = \App\Models\Order_header::find($resourceId);
            $customer_rec = \App\Models\Customer::find($order->customer_rec_id);

            return $query->where('district', $customer_rec->district);
        }
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
