<?php

namespace App\Nova;

use App\Nova\Actions\BranchReceipt;
use App\Nova\Filters\BranchBalanceFilter;
use App\Nova\Metrics\OrderBranchPerDay;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;

class Branch_balance extends Resource
{
    public static $group = '8.สำหรับสาขา';
    public static $priority = 4;
    public static $polling = false;
    public static $pollingInterval = 90;
    public static $showPollingToggle = true;
    public static $globallySearchable = false;
    /**
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Branch_balance::class;

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
        return 'รายการเก็บเงินปลายทางสาขา';
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
                ->hideFromIndex(),
            Date::make('วันที่ตั้งหนี้', 'branchbal_date')
                ->sortable()
                ->format('DD-MM-YYYY'),
            BelongsTo::make(__('Customer'), 'customer', 'App\Nova\Customer')
                ->sortable(),

            Currency::make('จำนวนเงิน', 'bal_amount')
                ->sortable(),
            Currency::make('ส่วนลด', 'discount_amount')
                ->sortable()
                ->hideFromIndex(),
            Currency::make('ภาษี', 'tax_amount')
                ->hideFromIndex(),

            Currency::make('ยอดรับชำระ', 'pay_amount')
                ->sortable(),

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
            BelongsTo::make('ใบเสร็จรับเงิน', 'receipt', 'App\Nova\Receipt')->sortable(),
            HasMany::make('รายการใบรับส่ง', 'branch_balance_items', 'App\Nova\Branch_balance_item')

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
            new BranchBalanceFilter(),
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
            BranchReceipt::make($request->resourceId)
                ->onlyOnDetail()
                ->confirmText('ยืนยันการรับชำระค่าขนส่งรายการนี้?')
                ->confirmButtonText('ยืนยัน')
                ->cancelButtonText("ไม่ยืนยัน")
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('view branch_balance');
                })
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('type', 'owner');
    }
}
