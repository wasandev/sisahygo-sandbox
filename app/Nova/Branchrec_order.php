<?php

namespace App\Nova;

use App\Nova\Actions\CreateBranchDeliveryItems;
use App\Nova\Actions\CreateTruckDeliveryItems;
use App\Nova\Actions\MakeOrderBranchWarehouse;
use App\Nova\Actions\OrderReceived;
use App\Nova\Filters\ByWaybill;
use App\Nova\Filters\ShowByOrderStatusBranch;
use App\Nova\Filters\ToBranch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Wasandev\Orderstatus\Orderstatus;


class Branchrec_order extends Resource
{
    public static $group = '8.สำหรับสาขา';
    public static $priority = 2;
    public static $polling = false;
    public static $pollingInterval = 90;
    public static $showPollingToggle = true;
    public static $perPageOptions = [50, 100, 150];

    public static function availableForNavigation(Request $request)
    {
        return $request->user()->hasPermissionTo('manage branchrec_orders');
    }
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Branchrec_order::class;

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
    public static $defaultSort = 'to_customer';
    public static $search = [
        'id', 'order_header_no'
    ];

    public static $searchRelations = [
        'to_customer' => ['name']
    ];
    public static $globalSearchRelations = [
        'to_customer' => ['name']
    ];
    public static function label()
    {
        return 'รายการใบรับส่งเข้าสาขา';
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
            BelongsTo::make('ใบกำกับสินค้า', 'branchrec_waybill', 'App\Nova\Branchrec_waybill')
                ->nullable()
                ->sortable()
                ->readonly(),
            Text::make('อำเภอ', 'districe', function () {
                return $this->to_customer->district;
            })->onlyOnIndex(),
            Status::make(__('Order status'), 'order_status')
                ->loadingWhen(['in transit'])
                ->failedWhen(['cancel'])
                ->exceptOnForms(),
            Select::make(__('Payment type'), 'paymenttype')->options([
                'H' => 'เงินสดต้นทาง',
                'T' => 'เงินโอนต้นทาง',
                'E' => 'เงินสดปลายทาง',
                'F' => 'วางบิลต้นทาง',
                'L' => 'วางบิลปลายทาง'
            ])->onlyOnIndex(),

            Boolean::make(__('Payment status'), 'payment_status')
                ->exceptOnForms(),
            Select::make(__('Tran type'), 'trantype')->options([
                '0' => 'รับเอง',
                '1' => 'จัดส่ง',
            ])->displayUsingLabels()
                ->sortable(),
            BelongsTo::make('ผู้รับสินค้า', 'to_customer', 'App\Nova\Customer')
                ->sortable()
                ->exceptOnForms(),
            Text::make(__('Order header no'), 'order_header_no')
                ->readonly()
                ->sortable(),


            BelongsTo::make(__('To branch'), 'to_branch', 'App\Nova\Branch')
                ->onlyOnDetail(),



            BelongsTo::make('ผู้ส่งสินค้า', 'customer', 'App\Nova\Customer')
                ->sortable()
                ->exceptOnForms()
                ->hideFromIndex(),
            Currency::make('จำนวนเงิน', 'order_amount')
                ->exceptOnForms(),
            BelongsTo::make(__('Loader'), 'loader', 'App\Nova\User')
                ->nullable()
                ->searchable()
                ->hideFromIndex(),

            Text::make(__('Remark'), 'remark')->nullable()
                ->onlyOnDetail(),


            HasMany::make(__('Order detail'), 'order_details', 'App\Nova\Order_detail'),
            HasMany::make(__('Order status'), 'order_statuses', 'App\Nova\Order_status'),
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
            (new Orderstatus()),
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
            new ToBranch(),
            new ByWaybill(),
            new ShowByOrderStatusBranch(),
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

            (new CreateTruckDeliveryItems($request->resourceId))
                ->confirmText('ต้องการทำ -รายการจัดส่งโดยรถบรรทุก- จากใบรับส่งที่เลือกไว้')
                ->confirmButtonText('ใช่')
                ->cancelButtonText("ไม่ใช่")
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('manage branchrec_orders');
                })
                ->canSee(function ($request) {
                    return  $request->user()->hasPermissionTo('manage branchrec_orders');
                }),
            (new MakeOrderBranchWarehouse())
                ->confirmText('ต้องการทำ -รายการลงสินค้าไว้สาขา- จากใบรับส่งที่เลือกไว้')
                ->confirmButtonText('ใช่')
                ->cancelButtonText("ไม่ใช่")
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('manage branchrec_orders');
                })
                ->canSee(function ($request) {
                    return  $request->user()->hasPermissionTo('manage branchrec_orders');
                }),
            (new CreateBranchDeliveryItems())
                ->confirmText('ต้องการทำ -รายการจัดส่งโดยรถสาขา- จากใบรับส่งที่เลือกไว้')
                ->confirmButtonText('ใช่')
                ->cancelButtonText("ไม่ใช่")
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('manage branchrec_orders');
                })->canSee(function ($request) {
                    return  $request->user()->hasPermissionTo('manage branchrec_orders');
                }),
            (new OrderReceived($request->resourceId))
                ->onlyOnDetail()
                ->confirmText('ทำรายการยืนยันการรับสินค้า จากใบรับส่งที่เลือกไว้')
                ->confirmButtonText('ยืนยัน')
                ->cancelButtonText("ไม่ยืนยัน")
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('manage branchrec_orders');
                })->canSee(function ($request) {
                    return  $request->user()->hasPermissionTo('manage branchrec_orders');
                }),

        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {


        $resourceTable = 'order_headers';
        $query->select("{$resourceTable}.*");
        $query->addSelect('c.district as customerDistrict');
        $query->join('customers as c', "{$resourceTable}.customer_rec_id", '=', 'c.id');

        $query->when(empty($request->get('orderBy')), function (Builder $q) use ($resourceTable) {
            $q->getQuery()->orders = null;
            return $q->orderBy('customerDistrict', 'asc')
                ->orderBy('order_headers.waybill_id', 'desc')
                ->orderBy('order_headers.id', 'asc');
        });
    }
}
