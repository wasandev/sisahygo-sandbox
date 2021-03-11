<?php

namespace App\Nova;

use App\Nova\Filters\BankTransferStatus;
use App\Nova\Filters\Transfertype;
use App\Nova\Metrics\OrderTransferPerDay;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class Order_banktransfers extends Resource
{
    public static $group = '9.2 งานการเงิน/บัญชี';
    public static $priority = 5;
    public static $polling = true;
    public static $pollingInterval = 90;
    public static $showPollingToggle = true;
    public static $globallySearchable = false;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order_banktransfer::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'order_header_id';

    public static function availableForNavigation(Request $request)
    {
        return $request->user()->hasPermissionTo('edit order_banktransfers');
    }

    // public function title()
    // {
    //     return $this->order_header->order_header_no;
    // }
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'account_no'
    ];
    public static function label()
    {
        return 'รายการโอนเงินค่าขนส่ง';
    }
    public static function singularLabel()
    {
        return 'รายการโอนเงินค่าขนส่ง';
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
            Boolean::make(__('Status'), 'status'),
            Select::make('ประเภทรายการ', 'transfer_type')->options([
                'H' => 'ต้นทาง',
                'B' => 'รับชำระหนี้',
                'E' => 'ปลายทาง'
            ])->displayUsingLabels()
                ->exceptOnForms(),
            BelongsTo::make(__('Branch'), 'branch', 'App\Nova\Branch')
                ->hideFromIndex(),
            BelongsTo::make(__('Customer name'), 'customer', 'App\Nova\Customer')
                ->sortable(),
            BelongsTo::make(__('Order header no'), 'order_header', 'App\Nova\Order_header')
                ->sortable(),
            BelongsTo::make(__('Bank Account no'), 'bankaccount', 'App\Nova\Bankaccount'),
            Currency::make(__('Amount'), 'transfer_amount'),
            Text::make(__('Bank reference no'), 'reference')
                ->hideFromIndex(),
            BelongsTo::make('ใบเสร็จรับเงิน', 'receipt_all', 'App\Nova\Receipt_all'),
            Image::make('สลิปโอนเงิน', 'transferslip')
                ->hideFromIndex(),
            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->onlyOnDetail(),
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
            new OrderTransferPerDay(),
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
            new Transfertype,
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
            (new Actions\ConfirmBanktransfer($request->resourceId))
                //->onlyOnDetail()
                ->confirmText('ต้องการยืนยันรายการโอนเงิน รายการนี้?')
                ->confirmButtonText('ยืนยัน')
                ->cancelButtonText("ไม่ยืนยัน")
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit order_banktransfers');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit order_banktransfers');
                })
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($request->user()->branch->type == 'partner') {

            return   $query->where('branch_id', $request->user()->branch_id);
        }
        return $query;
    }
}
