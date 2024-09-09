<?php

namespace App\Nova;

use App\Nova\Actions\BranchReceipt;
use App\Nova\Actions\BranchReceiptGroup;
use App\Nova\Filters\BranchBalanceFilter;
use App\Nova\Filters\BranchbalanceFromDate;
use App\Nova\Filters\BranchBalanceStatus;
use App\Nova\Filters\BranchbalanceToDate;
use App\Nova\Filters\BranchPayFromDate;
use App\Nova\Filters\BranchPayToDate;
use App\Nova\Filters\PaymentStatus;
use App\Nova\Lenses\Branch\BranchBalanceBydate;
use App\Nova\Lenses\Branch\BranchBalanceReceipt;
use App\Nova\Lenses\Branch\BranchBalanceReport;
use App\Nova\Metrics\OrderBranchPerDay;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Text;

class Branch_balance_partner extends Resource
{
    public static $group = '8.สำหรับสาขา';
    public static $priority = 6;
    public static $polling = false;
    public static $pollingInterval = 90;
    public static $showPollingToggle = true;
    public static $globallySearchable = false;
    public static $with = ['customer', 'branchrec_order', 'receipt', 'user', 'branch'];

    /**

     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Branch_balance_partner::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return $this->id;
    }


    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'order_header_id'
    ];

    public static $searchRelations = [
        'customer' => ['name'], 'branchrec_order' => ['order_header_no'],
        'receipt' => ['receipt_no']
    ];
    public static function label()
    {
        return 'รายการเก็บเงินปลายทางสาขาร่วม';
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
            Boolean::make('สถานะการชำระ', 'payment_status')
                ->sortable(),


            BelongsTo::make(__('Branch'), 'branch', 'App\Nova\Branch')
                ->sortable()
                ->exceptOnForms(),
            BelongsTo::make('ใบรับส่งสินค้า', 'branchrec_order', 'App\Nova\Branchrec_order')
                ->sortable()
                ->exceptOnForms(),

            Date::make('วันที่ตั้งหนี้', 'branchbal_date')
                ->sortable()
                ->format('DD-MM-YYYY')
                ->exceptOnForms(),
            BelongsTo::make(__('Customer'), 'customer', 'App\Nova\Customer')
                ->sortable()->exceptOnForms(),
            BelongsTo::make('ใบจัดส่ง', 'delivery', 'App\Nova\Delivery')
                ->sortable()->exceptOnForms()->nullable(),

            Currency::make('ค่าขนส่ง', 'bal_amount')
                ->sortable()
                ->readonly(),
            Date::make('วันที่รับชำระ', 'branchpay_date')
                ->sortable()
                ->format('DD-MM-YYYY'),

            Currency::make('ส่วนลด', 'discount_amount')
                ->sortable(),
            Currency::make('ภาษี', 'tax_amount')
                ->hideFromIndex(),
            Currency::make('ยอดรับชำระ', 'pay_amount'),





            Text::make('ชำระโดย',  function () {
                if (isset($this->receipt_id)) {
                    if ($this->receipt->branchpay_by === 'T') {
                        return 'โอน';
                    } else {
                        return 'เงินสด';
                    }
                } else {
                    return '-';
                }
            })->hideFromIndex(),
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
            BelongsTo::make('ใบเสร็จรับเงิน', 'receipt', 'App\Nova\Receipt')->sortable()->readonly(),

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
            new PaymentStatus(),
            new BranchBalanceFilter(),
            new BranchPayFromDate(),
            new BranchPayToDate(),
            new BranchbalanceFromDate(),
            new BranchbalanceToDate()

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
            new BranchBalanceBydate(),
            new BranchBalanceReceipt(),
            new BranchBalanceReport(),
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
            BranchReceipt::make($request->resourceId)
                ->onlyOnDetail()
                ->confirmText('ยืนยันการรับชำระค่าขนส่งรายการนี้?')
                ->confirmButtonText('ยืนยัน')
                ->cancelButtonText("ไม่ยืนยัน")
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('view branch_balance');
                }),
            BranchReceiptGroup::make($request->resourceId)
                ->onlyOnIndex()
                ->confirmText('ต้องการยืนยันการรับชำระค่าขนส่งตามรายการที่เลือกไว้นี้?')
                ->confirmButtonText('ยืนยัน')
                ->cancelButtonText("ไม่ยืนยัน")
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('view branch_balance');
                })
            
        ];
    }
    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($request->user()->branch->code <> '001') {
            return  $query->where('branch_id', $request->user()->branch_id)
                ->where('type', 'partner');
        } else {
            return $query->where('type', 'partner');
        }
    }
    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return '/resources/' . static::uriKey();
    }

    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/' . static::uriKey();
    }
}
