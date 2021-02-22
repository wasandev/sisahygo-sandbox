<?php

namespace App\Nova;

use App\Nova\Actions\AddOrderToInvoice;
use App\Nova\Actions\CreateInvoice;
use App\Nova\Actions\RemoveOrderFromInvoice;
use App\Nova\Filters\ArbalanceByCustomer;
use App\Nova\Filters\ArbalanceFromDate;
use App\Nova\Filters\ArbalanceNotInvoice;
use App\Nova\Filters\ArbalanceNotReceipt;
use App\Nova\Filters\ArbalanceToDate;
use App\Nova\Metrics\OrderBillPerDay;
use App\Nova\Metrics\OrderBranchPerDay;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\ActionRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Database\Eloquent\Model;

class Ar_balance extends Resource
{
    public static $group = '9.1 งานลูกหนี้การค้า';
    public static $priority = 2;
    public static $polling = false;
    public static $pollingInterval = 90;
    public static $showPollingToggle = true;
    public static $globallySearchable = false;
    public static $preventFormAbandonment = true;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Ar_balance::class;

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
        'id',
    ];
    public static $searchRelations = [
        'ar_customer' => ['name'],
    ];
    public static function label()
    {
        return 'รายการวางบิล';
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
            Boolean::make('การชำระเงิน', 'status', function () {
                return $this->order_header->payment_status;
            }),
            Date::make('วันที่ตั้งหนี้', 'created_at')
                ->format('DD/MM/YYYY')
                ->sortable(),
            BelongsTo::make('ชื่อลูกค้า', 'ar_customer', 'App\Nova\Ar_customer')
                ->searchable()
                ->sortable()
                ->readonly(),
            BelongsTo::make('เลขที่ใบรับส่ง', 'order_header', 'App\Nova\Order_header')
                ->sortable(),

            Currency::make('จำนวนเงิน', 'ar_amount')
                ->sortable(),
            BelongsTo::make('ใบแจ้งหนี้', 'invoice', 'App\Nova\Invoice')
                ->sortable(),
            BelongsTo::make('ใบเสร็จรับเงิน', 'receipt_ar', 'App\Nova\Receipt_ar')
                ->sortable(),


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
            new OrderBillPerDay(),
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
            new ArbalanceByCustomer,
            new ArbalanceNotInvoice,
            new ArbalanceNotReceipt,
            new ArbalanceFromDate,
            new ArbalanceToDate
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
            (new CreateInvoice)
                ->showOnIndex()
                ->confirmText('ต้องการสร้างใบแจ้งหนี้จากใบรับส่งที่เลือกไว้')
                ->confirmButtonText('ใช่')
                ->cancelButtonText("ไม่ใช่")
                ->canRun(function ($request, $model) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('edit invoices');
                })
                ->canSee(function ($request) {
                    if ($request instanceof ActionRequest) {
                        return true;
                    }
                    return $this->resource instanceof Model && $this->resource->invoice_id === null;
                }),

            (new RemoveOrderFromInvoice)

                ->confirmText('ต้องการนำใบรับส่งที่เลือกไว้ออกจากใบแจ้งหนี้?')
                ->confirmButtonText('ใช่')
                ->cancelButtonText("ไม่ใช่")
                ->canRun(function ($request, $model) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('edit invoices');
                })
                ->canSee(function ($request) {
                    if ($request instanceof ActionRequest) {
                        return true;
                    }
                    return $this->resource instanceof Model && $this->resource->invoice_id <> null;
                }),
            (new AddOrderToInvoice($request->resourceId))
                ->onlyOnDetail()
                ->confirmText('ต้องการนำใบรับส่งที่เลือกไว้เข้าใบแจ้งหนี้?')
                ->confirmButtonText('ใช่')
                ->cancelButtonText("ไม่ใช่")
                ->canRun(function ($request) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('edit invoices');
                })
                ->canSee(function ($request) {
                    if ($request instanceof ActionRequest) {
                        return true;
                    }
                    return $this->resource instanceof Model && $this->resource->invoice_id === null;
                }),
        ];
    }
}
