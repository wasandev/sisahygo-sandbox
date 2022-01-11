<?php

namespace App\Nova;

use App\Nova\Actions\PrintOrder;
use App\Nova\Filters\BillingUser;
use App\Nova\Filters\CheckerUser;
use App\Nova\Filters\OrderdateFilter;
use App\Nova\Filters\OrderFromDate;
use App\Nova\Filters\OrderToDate;
use App\Nova\Filters\ShowOwnOrder;
use App\Nova\Filters\ShowByOrderStatus;
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
use App\Nova\Metrics\OrderIncomes;
use App\Nova\Metrics\OrdersByPaymentType;
use App\Nova\Metrics\OrdersPerDay;
use App\Nova\Metrics\OrdersByBranchRec;
use App\Nova\Metrics\OrdersPerMonth;
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Laravel\Nova\Http\Requests\ActionRequest;
use Wasandev\Orderstatus\Orderstatus;

class Order_charter extends Resource
{
    use HasDependencies;

    public static $group = "6.งานขนส่งแบบเหมา";
    public static $priority = 7;
    public static $trafficCop = false;
    public static $preventFormAbandonment = true;
    public static $perPageOptions = [50, 100, 150];

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order_charter::class;


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
        'order_header_no', 'id'
    ];

    public static $searchRelations = [
        'customer' => ['name'],
    ];
    public static $globalSearchRelations = [
        'customer' => ['name'],
    ];

    public static function label()
    {

        return "รายการใบรับส่งเหมาคัน";
    }
    public static function singularLabel()
    {
        return 'ใบรับส่งเหมาคัน';
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
            ID::make('ลำดับ', 'id')
                ->sortable(),
            Status::make(__('Order status'), 'order_status')
                ->loadingWhen(['new'])
                ->failedWhen(['cancel', 'problem'])
                ->exceptOnForms()
                ->sortable(),
            Date::make(__('Order date'), 'order_header_date')
                ->readonly()
                ->default(today())
                ->format('DD/MM/YYYY')
                ->exceptOnForms()
                ->sortable(),
            Text::make(__('Order header no'), 'order_header_no')
                ->readonly()
                ->sortable(),
            BelongsTo::make('ใบกำกับสินค้า', 'waybill_charter', 'App\Nova\Waybill_charter')
                ->nullable(),
            //->onlyOnDetail(),


            Select::make(__('Payment type'), 'paymenttype')->options([
                'H' => 'เงินสดต้นทาง',
                'T' => 'เงินโอนต',
                'F' => 'วางบิล',
            ])->displayUsingLabels()
                ->hideFromIndex(),
            Boolean::make(__('Payment status'), 'payment_status')
                ->exceptOnForms(),
            BelongsTo::make(__('Branch'), 'branch', 'App\Nova\Branch')
                ->hideWhenCreating()
                ->hideFromIndex(),


            BelongsTo::make('ลูกค้า', 'customer', 'App\Nova\Customer')
                ->searchable()
                ->withSubtitles()
                ->showCreateRelationButton()
                ->sortable(),

            Currency::make('จำนวนเงิน', 'order_amount')
                ->exceptOnForms(),

            Text::make(__('Remark'), 'remark')->nullable()
                ->hideFromIndex(),

            BelongsTo::make('พนักงานออกใบรับส่ง', 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Updated At'), 'updated_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            HasMany::make(__('Order detail'), 'order_details', 'App\Nova\Order_detail'),
            //HasMany::make(__('Order status'), 'order_statuses', 'App\Nova\Order_status'),
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
            // new ShowByOrderStatus(),
            // new ShowOwnOrder(),
            // new OrderFromDate(),
            // new OrderToDate(),
            // new BillingUser(),
            // new CheckerUser(),

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
        return [
            // new Lenses\OrderBillingCash(),
        ];
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

            // (new Actions\OrderConfirmed($request->resourceId))
            //     ->onlyOnDetail()
            //     ->confirmText('ต้องการยืนยันใบรับส่งรายการนี้?')
            //     ->confirmButtonText('ยืนยัน')
            //     ->cancelButtonText("ไม่ยืนยัน")
            //     ->canRun(function ($request, $model) {
            //         return $request->user()->hasPermissionTo('manage order_headers');
            //     })
            //     ->canSee(function ($request) {
            //         return $request instanceof ActionRequest
            //             || ($request->user()->hasPermissionTo('manage order_headers') && ($this->resource->exists && $this->resource->order_status == 'new'));
            //     }),
            (new Actions\PrintOrder)
                ->onlyOnDetail()
                ->confirmText('ต้องการพิมพ์ใบรับส่งรายการนี้?')
                ->confirmButtonText('พิมพ์')
                ->cancelButtonText("ไม่พิมพ์")
                ->canRun(function ($request, $model) {
                    return $request->user()->hasPermissionTo('view order_headers');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view order_headers');
                }),
            (new Actions\CancelOrder())
                ->confirmText('ต้องการยกเลิกใบรับส่งรายการนี้?')
                ->confirmButtonText('ยกเลิก')
                ->cancelButtonText("ไม่ยกเลิก")
                ->canRun(function ($request, $model) {
                    return $request->user()->hasPermissionTo('manage order_headers');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('manage order_headers');
                }),
            // (new Actions\OrderProblem())
            //     ->onlyOnDetail()
            //     ->confirmText('แจ้งปัญหาใบรับส่งรายการนี้?')
            //     ->confirmButtonText('ตกลง')
            //     ->cancelButtonText('ยกเลิก')
            //     ->canRun(function ($request) {
            //         return $request->user()->hasPermissionTo('manage order_headers');
            //     })
            //     ->canSee(function ($request) {
            //         return $request->user()->hasPermissionTo('manage order_headers');
            //     }),
        ];
    }
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('order_type', '=', 'charter');
    }
}
