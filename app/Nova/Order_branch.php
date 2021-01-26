<?php

namespace App\Nova;

use App\Nova\Actions\PrintOrder;
use App\Nova\Filters\BillingUser;
use App\Nova\Filters\OrderFromDate;
use App\Nova\Filters\OrderToDate;
use App\Nova\Filters\ToBranch;
use App\Nova\Metrics\OrderBranchPerDay;
use App\Nova\Metrics\OrderCashPerDay;
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
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;

use Wasandev\Orderstatus\Orderstatus;

class Order_branch extends Resource
{
    use HasDependencies;
    public static $polling = true;
    public static $pollingInterval = 60;
    public static $showPollingToggle = true;
    public static $group = '9.งานการเงิน/บัญชี';
    public static $priority = 6;
    public static $trafficCop = false;
    public static $preventFormAbandonment = true;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order_branch::class;


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

        return "รายการเงินสดปลายทาง";
    }
    public static function singularLabel()
    {
        return 'เงินสดปลายทาง';
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
                ->sortable()
                ->hideFromIndex(),
            Status::make(__('Order status'), 'order_status')
                ->loadingWhen(['new'])
                ->failedWhen(['cancel', 'problem'])
                ->exceptOnForms(),
            Boolean::make('การชำระ', 'payment_status'),
            BelongsTo::make('สาขา', 'to_branch', 'App\Nova\Branch')
                ->sortable(),


            BelongsTo::make('ใบกำกับสินค้า', 'waybill', 'App\Nova\Waybill')
                ->nullable()
                ->onlyOnDetail(),
            Text::make(__('Order header no'), 'order_header_no')
                ->readonly(),
            Date::make(__('Order date'), 'order_header_date')
                ->readonly()
                ->default(today())
                ->format('DD/MM/YYYY')
                ->exceptOnForms(),

            Select::make(__('Payment type'), 'paymenttype')->options([
                'H' => 'เงินสดต้นทาง',
                'T' => 'เงินโอนต้นทาง',
                'E' => 'เงินสดปลายทาง',
                'F' => 'วางบิลต้นทาง',
                'L' => 'วางบิลปลายทาง'
            ])->displayUsingLabels()
                ->hideFromIndex(),


            BelongsTo::make('ใบเสร็จรับเงิน', 'receipt', 'App\Nova\Receipt'),


            BelongsTo::make(__('From Branch'), 'branch', 'App\Nova\Branch')
                ->onlyOnDetail(),

            BelongsTo::make('ลูกค้า', 'customer', 'App\Nova\Customer')
                ->onlyOnDetail(),

            BelongsTo::make('ผู้รับสินค้า', 'to_customer', 'App\Nova\Customer'),

            Currency::make('จำนวนเงิน', 'order_amount')
                ->exceptOnForms(),
            Select::make(__('Tran type'), 'trantype')->options([
                '0' => 'รับเอง',
                '1' => 'จัดส่ง',
            ])->displayUsingLabels()
                ->sortable()
                ->hideFromIndex(),
            Text::make(__('Remark'), 'remark')->nullable()
                ->hideFromIndex(),
            BelongsTo::make(__('Checker'), 'checker', 'App\Nova\User')
                ->hideFromIndex(),
            BelongsTo::make(__('Loader'), 'loader', 'App\Nova\User')
                ->onlyOnDetail(),
            BelongsTo::make(__('Shipper'), 'shipper', 'App\Nova\User')
                ->onlyOnDetail(),
            BelongsTo::make('พนักงาน', 'user', 'App\Nova\User')
                ->hideFromIndex(),
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
        return [
            new OrderBranchPerDay(),
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

            new OrderFromDate(),
            new OrderToDate(),
            new ToBranch()

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
            new Lenses\OrderBillingCash(),
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

            (new Actions\PrintOrder)->onlyOnDetail()
                ->confirmText('ต้องการพิมพ์ใบรับส่งรายการนี้?')
                ->confirmButtonText('พิมพ์')
                ->cancelButtonText("ไม่พิมพ์")
                ->canRun(function ($request, $model) {
                    return true;
                }),
        ];
    }
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->whereNotIn('order_status', ['checking', 'new'])
            ->where('paymenttype', '=', 'E');
    }
}
