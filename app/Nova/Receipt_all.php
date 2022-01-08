<?php

namespace App\Nova;

use App\Nova\Actions\PrintReceipt;
use App\Nova\Filters\ReceiptFromDate;
use App\Nova\Filters\ReceiptToDate;
use App\Nova\Filters\Receipttype;
use Laravel\Nova\Fields\Date;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Receipt_all extends Resource
{

    public static $group = '9.2 งานการเงิน/บัญชี';
    public static $priority = 6;
    public static $polling = true;
    public static $pollingInterval = 90;
    public static $showPollingToggle = true;
    public static $globallySearchable = false;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Receipt_all::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'receipt_no';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'receipt_no',
    ];
    public static function label()
    {
        return 'ใบเสร็จรับเงินทั้งหมด';
    }
    public static function availableForNavigation(Request $request)
    {
        return $request->user()->hasPermissionTo('edit receipt_all');
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
            Text::make('เลขที่ใบเสร็จรับเงิน', 'receipt_no'),
            Date::make('วันที่', 'receipt_date'),
            Select::make('ประเภทใบเสร็จ', 'receipttype')->options([
                'H' => 'ต้นทาง',
                'B' => 'วางบิล',
                'E' => 'ปลายทาง'
            ])->displayUsingLabels(),
            BelongsTo::make(__('Branch'), 'branch', 'App\Nova\Branch'),

            BelongsTo::make('ลูกค้า', 'customer', 'App\Nova\Customer'),
            Currency::make('จำนวนเงิน', 'total_amount'),
            Select::make('ชำระโดย', 'branchpay_by')->options([
                'C' => 'เงินสด',
                'T' => 'เงินโอน',
                'Q' => 'เช็ค',
                'R' => 'บัตรเครดิต'
            ])->displayUsingLabels(),
            BelongsTo::make('โอนเข้าบัญชี', 'bankaccount', 'App\Nova\Bankaccount')
                ->hideFromIndex()
                ->searchable()
                ->withSubtitles(),
            Text::make('เลขที่รายการโอน', 'bankreference')
                ->hideFromIndex(),
            Text::make('เช็คเลขที่', 'chequeno')
                ->hideFromIndex(),
            BelongsTo::make('เช็คของธนาคาร', 'chequebank', 'App\Nova\Bank')
                ->hideFromIndex(),
            Text::make('รายละเอียดอื่นๆ/หมายเหตุ', 'description')
                ->hideFromIndex(),
            Currency::make('ส่วนลด', 'discount_amount')
                ->hideFromIndex(),
            Currency::make('ภาษี', 'tax_amount')
                ->hideFromIndex(),
            Currency::make('ยอดรับชำระ', 'pay_amount')
                ->hideFromIndex(),
            HasMany::make('รายการใบรับส่ง', 'receipt_items', 'App\Nova\Receipt_item'),
            HasMany::make('รายการใบแจ้งหนี้', 'invoices', 'App\Nova\Invoice'),
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
            new Receipttype,
            new ReceiptFromDate,
            new ReceiptToDate,
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
            (new PrintReceipt())
                ->canRun(function ($request) {
                    return  $request->user()->hasPermissionTo('view receipt');
                })
                ->canSee(function ($request) {
                    return  $request->user()->hasPermissionTo('view receipt');
                })
        ];
    }
}
