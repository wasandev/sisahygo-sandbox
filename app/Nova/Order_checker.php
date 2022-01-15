<?php

namespace App\Nova;

use App\Nova\Filters\CheckerByUser;
use App\Nova\Filters\OrderdateFilter;
use App\Nova\Filters\OrderFromDate;
use App\Nova\Filters\OrderToDate;
use App\Nova\Filters\ShowByOrderStatus;
use App\Nova\Lensese\CheckingByUser;
use App\Nova\Metrics\CheckerbyUser as MetricsCheckerbyUser;
use App\Nova\Metrics\CheckerCancelbyUser;
use App\Nova\Metrics\CheckerProblembyUser;
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
use Wasandev\Orderstatus\Orderstatus;
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Laravel\Nova\Http\Requests\ActionRequest;
use Wasandev\QrCodeScan\QrCodeScan;

class Order_checker extends Resource
{
    use HasDependencies;
    public static $polling = true;
    public static $pollingInterval = 60;
    public static $showPollingToggle = true;
    public static $group = '7.งานบริการขนส่ง';
    public static $priority = 1;
    public static $globallySearchable = false;
    public static $preventFormAbandonment = true;
    public static $with = ['customer', 'to_customer', 'user'];
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order_checker::class;


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
        'id'
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

        return 'รายการตรวจรับสินค้า';
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
            Status::make(__('Order status'), 'order_status')
                ->loadingWhen(['checking'])
                ->failedWhen(['cancel'])
                ->exceptOnForms(),

            Date::make('วันที่', 'order_header_date')
                ->readonly()
                ->default(today())
                ->format('DD/MM/YYYY')
                ->exceptOnForms(),

            BelongsTo::make(__('From branch'), 'branch', 'App\Nova\Branch')
                ->exceptOnForms(),
            BelongsTo::make(__('To branch'), 'to_branch', 'App\Nova\Branch')
                //->hideWhenCreating()
                ->hideFromIndex()
                ->nullable()
                ->help('***โปรดระบุสาขา ถ้าที่อยู่ลูกค้าปลายทางอยู่นอกพื้นที่บริการของสาขาปลายทาง'),
            Select::make('ประเภท', 'order_type')->options([
                'general' => 'ทั่วไป',
                'express' => 'Express',
            ])->sortable()
                ->default('general')
                ->displayUsingLabels(),
            Boolean::make('สแกน Qr Code ผู้ส่ง', 'useqrcode')
                ->onlyOnForms(),
            NovaDependencyContainer::make([
                QrCodeScan::make('Qr code ผู้ส่ง', 'customer_id')   // Name -> label name, name_id -> save to column
                    ->canInput()                        // the user able to input the code using keyboard, default false
                    ->canSubmit()                       // on modal scan need to click submit to send the code to the input value, default false
                    ->displayValue()                    // set qr size on detail, default 100
                    ->qrSizeIndex()                     // set qr size on index, default 30
                    ->qrSizeDetail()                    // set qr size on detail, default 100
                    ->qrSizeForm()                      // set qr size on form, default 50
                    ->viewable()                        // set viewable if has belongto value, default true
                    ->displayWidth('320px')          // set display width, default auto

            ])->dependsOn('useqrcode', true)
                ->onlyOnForms(),
            NovaDependencyContainer::make([
                BelongsTo::make('ผู้ส่งสินค้า', 'customer', 'App\Nova\Customer')
                    ->searchable()
                    ->withSubtitles()
                    ->showCreateRelationButton()
            ])->dependsOn('useqrcode', false)
                ->onlyOnForms(),
            BelongsTo::make('ผู้ส่งสินค้า', 'customer', 'App\Nova\Customer')
                ->searchable()
                ->withSubtitles()
                ->exceptOnForms(),

            BelongsTo::make('ผู้รับสินค้า', 'to_customer', 'App\Nova\Customer')
                ->searchable()
                ->withSubtitles()
                ->showCreateRelationButton(),

            Select::make('การจัดส่ง', 'trantype')->options([
                '0' => 'รับเอง',
                '1' => 'จัดส่ง',
            ])->displayUsingLabels()
                ->sortable()
                ->default(1)
                ->hideFromIndex(),
            Text::make(__('Remark'), 'remark')->nullable()
                ->hideFromIndex(),
            BelongsTo::make(__('Checker'), 'checker', 'App\Nova\User')
                ->exceptOnForms()
                ->hideFromIndex(),
            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Updated At'), 'updated_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            HasMany::make(__('Order detail'), 'checker_details', 'App\Nova\Checker_detail'),
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
            (new Metrics\CheckerbyUserMetric())

                ->canSee(
                    function ($request) {
                        return $request->user()->role == 'admin';
                    }
                ),
            (new Metrics\CheckerCancelbyUser)

                ->canSee(
                    function ($request) {
                        return $request->user()->role == 'admin';
                    }
                ),
            (new Metrics\CheckerProblembyUser)

                ->canSee(
                    function ($request) {
                        return $request->user()->role == 'admin';
                    }
                ),
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
            new ShowByOrderStatus(),
            new OrderFromDate(),
            new OrderToDate(),
            new CheckerByUser()

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
            new CheckingByUser()
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

            (new Actions\OrderChecked($request->resourceId))
                ->onlyOnDetail()
                ->confirmText('ยืนยันรายการตรวจรับสินค้า?')
                ->confirmButtonText('ยืนยัน')
                ->cancelButtonText("ไม่ยืนยัน")
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('manage order_checkers');
                })
                ->canSee(function ($request) {
                    return $request instanceof ActionRequest
                        || ($this->resource->exists && $this->resource->order_status == 'checking');
                }),


        ];
    }
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('order_type', '<>', 'charter');
    }
    public static function relatableCustomers(NovaRequest $request, $query)
    {


        if ($request->route()->parameter('field') === "customer") {
            return $query->where('status', true);
        }

        if ($request->route()->parameter('field') === "to_customer") {
            return $query->where('status', true);
        }
    }
}
