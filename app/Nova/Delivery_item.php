<?php

namespace App\Nova;

use App\Nova\Actions\DeliveryConfirmed;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Fields\DateTime;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\ActionRequest;
use Laravel\Nova\Http\Requests\NovaRequest;

class Delivery_item extends Resource
{
    public static $displayInNavigation = false;
    public static $group = '8.สำหรับสาขา';
    public static $globallySearchable = false;
    public static $preventFormAbandonment = true;
    public static $perPageViaRelationship = 100;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Delivery_item::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return $this->customer->name;
    }


    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];
    public static $searchRelations = [
        'customer' => ['name'],
    ];
    public static function label()
    {
        return 'รายการจัดส่งตามผู้รับ';
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
            ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make('เลขที่รายการจัดส่ง', 'delivery', 'App\Nova\Delivery'),
            BelongsTo::make('ลูกค้า', 'customer', 'App\Nova\Customer')
                ->sortable()
                ->viewable(false),
            Date::make('วันที่รับชำระ', 'paydate')
                ->default(today()),
            
            Currency::make('ค่าขนส่งรวม',  function () {    
                $sumorder = 0; 
                $delivery_details =  \App\Models\Delivery_detail::where('delivery_item_id',$this->id)->get(); 
                foreach ($delivery_details as $orderitem ) {
                    $order = \App\Models\Branchrec_order::find($orderitem->order_header_id);
                    $sumorder += $order->order_amount ;
                }

                return $sumorder;
            })->exceptOnForms(),
            Currency::make('ยอดจัดเก็บ', 'payment_amount')
                ->exceptOnForms(),
            Boolean::make('สถานะการจัดส่ง', 'delivery_status')
                ->exceptOnForms(),
            Boolean::make('สถานะการเก็บเงิน', 'payment_status')->exceptOnForms(),
            Currency::make('ส่วนลด', 'discount_amount')
                ->onlyOnDetail(),
            Currency::make('ภาษีหัก ณ ที่จ่าย', 'tax_amount')
                ->onlyOnDetail(),
            Currency::make('จำนวนเงินรับชำระ', 'pay_amount')
                ->onlyOnDetail(),

            BelongsTo::make('ใบเสร็จรับเงิน', 'receipt', 'App\Nova\Receipt'),
            Text::make('หมายเหตุ', 'description'),

            HasMany::make('รายการใบรับส่ง', 'delivery_details', 'App\Nova\Delivery_detail'),


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
            (new DeliveryConfirmed($request->resourceId))
                ->onlyOnDetail()
                ->confirmText('ยืนยันการจัดส่งรายการนี้?')
                ->confirmButtonText('ยืนยัน')
                ->cancelButtonText("ไม่ยืนยัน")
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('view delivery_items');
                })
                ->canSee(function ($request) {
                    return $request instanceof ActionRequest
                        || ($this->resource->exists && $this->resource->delivery_status == false);
                }),
        ];
    }

    // public static function relatableBranchrec_orders(NovaRequest $request, $query)
    // {
    //     // if ($request->viaResourceId && $request->viaRelationship == 'delivery_items') {

    //     //     $resourceId = $request->viaResourceId;

    //     //     $order = \App\Models\Order_checker::find($resourceId);
    //     //     $district = $order->to_customer->district;
    //     //     //dd($district);
    //     //     return $query->orWhere('district', $district);
    //     // }
    //     return $query->whereIn('order_status', ['arrival', 'branch warehouse'])
    //         ->where('branch_rec_id', '=', $request->user()->branch_id);
    // }

    // public static function redirectAfterCreate(NovaRequest $request, $resource)
    // {
    //     return '/resources/' . $request->input('viaResource') . '/' . $request->input('viaResourceId');
    // }

    // public static function redirectAfterUpdate(NovaRequest $request, $resource)
    // {
    //     return '/resources/' . $request->input('viaResource') . '/' . $request->input('viaResourceId');
    // }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $resourceTable = 'delivery_items';
        $query->select("{$resourceTable}.*");
        $query->addSelect('c.district as customerDistrict');
        $query->join('customers as c', "{$resourceTable}.customer_id", '=', 'c.id');

        $query->when(empty($request->get('orderBy')), function (Builder $q) use ($resourceTable) {
            $q->getQuery()->orders = null;
            return $q->orderBy('customerDistrict', 'asc')
                ->orderBy('delivery_items.id', 'asc');
        });
    }
}
