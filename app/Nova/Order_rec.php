<?php

namespace App\Nova;


use App\Nova\Filters\ByBranchPaymentType;
use App\Nova\Filters\ByWaybill;
use App\Nova\Filters\OrderFromDate;
use App\Nova\Filters\OrderToDate;
use App\Nova\Filters\PaymentStatus;
use App\Nova\Filters\ShowByOrderStatusBranch;
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
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Wasandev\Orderstatus\Orderstatus;


class Order_rec extends Resource
{
    public static $displayInNavigation = false;
    public static $group = '8.สำหรับสาขา';
    public static $priority = 2;
    public static $polling = false;
    public static $pollingInterval = 90;
    public static $showPollingToggle = true;
    public static $perPageOptions = [50, 100, 150];
    public static $perPageViaRelationship = 200;
    public static $with = ['customer', 'to_customer', 'user'];
    public static $trafficCop = false;

    public static function availableForNavigation(Request $request)
    {
        return $request->user()->hasPermissionTo('manage branchrec_orders');
    }
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order_rec::class;

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
        'customer' => ['name'],
        'to_customer' => ['name', 'district', 'sub_district']
    ];
    public static $globalSearchRelations = [
        'to_customer' => ['name']
    ];
    public static function label()
    {
        return 'รายการรับสินค้า';
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
            Status::make(__('Order status'), 'order_status')
                ->loadingWhen(['in transit'])
                ->failedWhen(['cancel'])
                ->hideWhenCreating(),
            ID::make()->sortable(),
            Text::make(__('Order header no'), 'order_header_no')
                ->readonly()
                ->sortable(),

            // Text::make('ผู้ส่ง', 'cust_send', function () {
            //     return $this->customer->name;
            // })->onlyOnIndex(),
            BelongsTo::make('ผู้ส่งสินค้า', 'customer', 'App\Nova\Customer')
                ->sortable()
                ->exceptOnForms(),
            BelongsTo::make('ผู้รับสินค้า', 'to_customer', 'App\Nova\Customer')
                ->sortable()
                ->exceptOnForms(),
            Boolean::make(__('Payment status'), 'payment_status')
                ->exceptOnForms(),
            Text::make('อำเภอ', 'districe', function () {
                if(isset($this->to_customer->district)){
                    return $this->to_customer->district;
                }
                
            })->onlyOnIndex(),
            Currency::make('ค่าขนส่ง', 'order_amount')
                ->exceptOnForms(),
            Select::make(__('Payment type'), 'paymenttype')->options([
                'H' => 'เงินสดต้นทาง',
                'T' => 'เงินโอนต้นทาง',
                'E' => 'เงินสดปลายทาง',
                'F' => 'วางบิลต้นทาง',
                'L' => 'วางบิลปลายทาง'
            ])->onlyOnIndex(),



            BelongsTo::make(__('To branch'), 'to_branch', 'App\Nova\Branch')
                ->onlyOnDetail(),
            Text::make('โทรศัพท์สาขา', function () {
                return $this->to_branch->phoneno;
            })->onlyOnDetail(),






            Select::make(__('Tran type'), 'trantype')->options([
                '0' => 'รับเอง',
                '1' => 'จัดส่ง',
            ])->displayUsingLabels()
                ->sortable(),
            BelongsTo::make('ใบกำกับสินค้า', 'branchrec_waybill', 'App\Nova\Branchrec_waybill')
                ->nullable()
                ->sortable()
                ->readonly(),
            BelongsTo::make(__('Loader'), 'loader', 'App\Nova\User')
                ->nullable()
                ->searchable()
                ->hideFromIndex(),

            Text::make(__('Remark'), 'remark')->nullable()
                ->onlyOnDetail(),
            Text::make('ชื่อผู้รับสินค้า', 'order_recname')
                ->onlyOnDetail()
                ->nullable(),
            Text::make('เลขบัตรประชาชน', 'idcardno')
                ->onlyOnDetail()
                ->nullable(),

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
            // new ToBranch(),
            new PaymentStatus(),
            new ByBranchPaymentType(),
            new ShowByOrderStatusBranch(),
            new OrderFromDate(),
            new OrderToDate(),
            new ByWaybill(),

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

            // new ValueByBranch(),
            // new ValueByDistrict(),
            // new lenses\ValueByOrderConfirmed(),
            // new lenses\ValueByOrderBranchWarehouse(),
            // new lenses\ValueByOrderBranchCompletedNotPay()
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

            // (new DownloadExcel)->allFields()->withHeadings()
            //     ->canSee(function ($request) {
            //         return $request->user()->role == 'admin';
            //     }),

        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {


        // $resourceTable = 'order_headers';
        // $query->select("{$resourceTable}.*");
        // $query->addSelect('c.district as customerDistrict');
        // $query->join('customers as c', "{$resourceTable}.customer_rec_id", '=', 'c.id');

        // $query->when(empty($request->get('orderBy')), function (Builder $q) use ($resourceTable) {
        //     $q->getQuery()->orders = null;
        //     return $q->orderBy('customerDistrict', 'asc')
        //         ->orderBy('order_headers.waybill_id', 'desc')
        //         ->orderBy('order_headers.id', 'asc');
        // });

        // $branch = \App\Models\Branch::find($request->user()->branch_id);
        // if ($branch->code == '001') {
        //     return $query->whereNotIn('order_status', ['checking', 'new'])
        //         ->where('order_type', '<>', 'charter');
        // } else {
        //     return $query->whereNotIn('order_status', ['checking', 'new'])
        //         ->where('branch_rec_id', '=', $request->user()->branch_id)
        //         ->where('order_type', '<>', 'charter');
        // }
    }
}
