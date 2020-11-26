<?php

namespace App\Nova;

use App\Nova\Filters\OrderToBranch;
use App\Nova\Filters\ToDistrict;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class Order_loader extends Resource
{

    public static $group = '7.งานบริการขนส่ง';
    public static $priority = 4;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order_loader::class;


    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'order_header_no';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'order_header_no'
    ];

    public static $searchRelations = [
        'customer' => ['name'],
        'to_customer' => ['name']
    ];
    public static $globalSearchRelations = [
        'customer' => ['name'],
        'to_customer' => ['name']
    ];

    public static function label()
    {

        return 'รายการจัดขึ้นสินค้า';
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
            Status::make(__('Order status'), 'order_status')
                ->loadingWhen(['confirmed'])
                ->failedWhen(['cancel'])
                ->exceptOnForms(),

            BelongsTo::make('ใบกำกับสินค้า', 'waybill', 'App\Nova\Waybill')
                ->nullable(),
            Text::make(__('Order header no'), 'order_header_no')
                ->readonly(),


            BelongsTo::make(__('To branch'), 'to_branch', 'App\Nova\Branch')
                ->exceptOnForms(),

            BelongsTo::make('ผู้รับสินค้า', 'to_customer', 'App\Nova\Customer')
                ->sortable()
                ->exceptOnForms(),
            BelongsTo::make('ผู้ส่งสินค้า', 'customer', 'App\Nova\Customer')
                ->sortable()
                ->exceptOnForms()
                ->hideFromIndex(),
            BelongsTo::make(__('Loader'), 'loader', 'App\Nova\User')
                ->nullable()
                ->searchable()
                ->hideFromIndex(),

            Text::make(__('Remark'), 'remark')->nullable()
                ->onlyOnDetail(),


            HasMany::make(__('Order detail'), 'order_details', 'App\Nova\Order_detail'),
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
            new OrderToBranch(),
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
            (new Actions\OrderLoaded($request->resourceId))
                ->confirmText('ต้องการจัดสินค้าขึ้นรถใบรับส่งรายการนี้?')
                ->confirmButtonText('ยืนยัน')
                ->cancelButtonText("ไม่ยืนยัน")
                ->canRun(function ($request, $model) {
                    return true;
                }),
        ];
    }
    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($request->user()->role != 'admin') {
            return $query->where('order_status', 'confirmed')
                ->orWhere('order_status', 'loaded');
        }
        return $query;
    }
    // public static function relatableWaybills(NovaRequest $request, $query)
    // {
    //     $to_branch =  $request;

    //     if ($request->route()->parameter('field') == "to_customer") {
    //         $branch_area = \App\Models\Branch_area::where('branch_id', $to_branch)->get();
    //         return $query->whereIn('district', $branch_area);
    //     }
    //     //return $query;
    // }


}
