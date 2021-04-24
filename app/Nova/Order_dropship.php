<?php

namespace App\Nova;

use App\Nova\Actions\PrintOrder;
use App\Nova\Filters\BillingUser;
use App\Nova\Filters\CheckerUser;
use App\Nova\Filters\OrderdateFilter;
use App\Nova\Filters\OrderDropshipFromDate;
use App\Nova\Filters\OrderDropshipToDate;
use App\Nova\Filters\OrderFromBranch;
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

class Order_dropship extends Resource
{
    use HasDependencies;
    public static $polling = true;
    public static $pollingInterval = 60;
    public static $showPollingToggle = true;
    public static $group = '7.งานบริการขนส่ง';
    public static $priority = 0;
    public static $trafficCop = false;
    public static $preventFormAbandonment = true;
    public static $perPageOptions = [50, 100, 150];

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order_dropship::class;


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
        'order_header_no', 'tracking_no', 'id'
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

        return "ใบรับส่งสินค้าจากตัวแทน";
    }
    public static function singularLabel()
    {
        return 'ใบรับส่งสินค้าจากตัวแทน';
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
            Select::make('สถานะสินค้า', 'shipto_center')->options([
                '0' => 'อยู่จุดรับสินค้า',
                '1' => 'ออกจากจุดรับสินค้าแล้ว',
                '2' => 'ถึงสำนักงานใหญ่'
            ])->displayUsingLabels(),
            Select::make('ประเภท', 'order_type')->options([
                'general' => 'ทั่วไป',
                'express' => 'Express',
            ])->sortable()
                ->default('general')
                ->displayUsingLabels(),
            BelongsTo::make(__('From branch'), 'branch', 'App\Nova\Branch')
                ->hideWhenCreating(),
            //->hideFromIndex(),

            BelongsTo::make(__('To branch'), 'to_branch', 'App\Nova\Branch')
                ->hideWhenCreating()
                ->showOnUpdating(),
            BelongsTo::make('ใบกำกับสินค้า', 'waybill', 'App\Nova\Waybill')
                ->nullable()
                ->onlyOnDetail(),
            Date::make(__('Order date'), 'order_header_date')
                ->readonly()
                ->default(today())
                ->format('DD/MM/YYYY')
                ->exceptOnForms()
                ->sortable(),
            Text::make(__('Order header no'), 'order_header_no')
                ->readonly()
                ->sortable(),
            Text::make(__('Tracking no'), 'tracking_no')
                ->onlyOnDetail(),


            Select::make(__('Payment type'), 'paymenttype')->options([
                'H' => 'เงินสดต้นทาง',
                'T' => 'เงินโอนต้นทาง',
                'E' => 'เงินสดปลายทาง',
                'F' => 'วางบิลต้นทาง',
                'L' => 'วางบิลปลายทาง'
            ])->onlyOnIndex(),
            Select::make(__('Payment type'), 'paymenttype')->options([
                'H' => 'เงินสดต้นทาง',
                'T' => 'เงินโอนต้นทาง',
                'E' => 'เงินสดปลายทาง',
                'F' => 'วางบิลต้นทาง',
                'L' => 'วางบิลปลายทาง'
            ])->displayUsingLabels()
                ->onlyOnDetail(),
            Boolean::make(__('Payment status'), 'payment_status')
                ->exceptOnForms(),



            BelongsTo::make('ผู้ส่งสินค้า', 'customer', 'App\Nova\Customer')
                ->searchable()
                ->withSubtitles()
                ->showCreateRelationButton()
                ->sortable(),


            BelongsTo::make('ผู้รับสินค้า', 'to_customer', 'App\Nova\Customer')
                ->searchable()
                ->withSubtitles()
                ->showCreateRelationButton()
                ->sortable(),


            Currency::make('จำนวนเงิน', 'order_amount')
                ->exceptOnForms(),
            Select::make(__('Tran type'), 'trantype')->options([
                '0' => 'รับเอง',
                '1' => 'จัดส่ง',
            ])->displayUsingLabels()
                ->sortable()
                ->hideFromIndex()
                ->default(1),
            Text::make(__('Remark'), 'remark')->nullable()
                ->hideFromIndex(),
            BelongsTo::make(__('Checker'), 'checker', 'App\Nova\User')
                ->hideFromIndex()
                ->searchable(),
            BelongsTo::make(__('Loader'), 'loader', 'App\Nova\User')
                ->onlyOnDetail(),
            BelongsTo::make(__('Shipper'), 'shipper', 'App\Nova\User')
                ->onlyOnDetail(),
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
            new OrderFromBranch(),
            new OrderDropshipFromDate(),
            new OrderDropshipToDate(),

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


            (new Actions\PrintOrder)
                ->onlyOnDetail()
                ->confirmText('ต้องการพิมพ์ใบรับส่งรายการนี้?')
                ->confirmButtonText('พิมพ์')
                ->cancelButtonText("ไม่พิมพ์")
                ->canRun(function ($request, $model) {
                    return $request->user()->hasPermissionTo('manage order_headers');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('manage order_headers');
                }),

            (new Actions\OrderProblem())
                ->onlyOnDetail()
                ->confirmText('แจ้งปัญหาใบรับส่งรายการนี้?')
                ->confirmButtonText('ตกลง')
                ->cancelButtonText('ยกเลิก')
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('manage order_headers');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('manage order_headers');
                }),
        ];
    }
    public static function indexQuery(NovaRequest $request, $query)
    {
        $dropship = \App\Models\Branch::where('dropship_flag', true)->get('id');

        return $query->where('order_status', '<>', 'checking')
            ->whereIn('branch_id', $dropship)
            ->where('order_type', '<>', 'charter');
    }
    public static function relatableCustomers(NovaRequest $request, $query)
    {
        $from_branch = $request->user()->branch_id;
        $to_branch =  $request->user()->branch_rec_id;
        $dropship_flag = \App\Models\Branch::find($from_branch)->dropship_flag;
        if ($dropship_flag) {
            $mainbranch = \App\Models\Branch::where('code', '001')->first();
            $from_branch_area = \App\Models\Branch_area::where('branch_id', $mainbranch->id)
                ->get('district');
        } else {
            $from_branch_area = \App\Models\Branch_area::where('branch_id', $from_branch)
                ->get('district');
        }

        if (!is_null($from_branch)) {
            if ($request->route()->parameter('field') === "customer") {
                // $branch_area = \App\Models\Branch_area::where('branch_id', $from_branch)
                //     ->get('district');

                return $query->whereIn('district', $from_branch_area);
            }
        }
        if (is_null($to_branch)) {
            if ($request->route()->parameter('field') === "to_customer") {
                $to_branch_area = \App\Models\Branch_area::whereNotIn('district', $from_branch_area)->get('district');
                return $query->whereIn('district', $to_branch_area);
            }
        } else {
            if ($request->route()->parameter('field') === "to_customer") {
                $to_branch_area = \App\Models\Branch_area::where('branch_id', $to_branch)->get('district');
                return $query->whereIn('district', $to_branch_area);
            }
        }
    }
}
