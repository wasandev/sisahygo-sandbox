<?php

namespace App\Nova;

use App\Nova\Actions\CancelReceipt;
use App\Nova\Actions\PrintReceipt;
use App\Nova\Filters\ArbalanceByCustomer;
use App\Nova\Filters\Customer;
use App\Nova\Filters\ReceiptByCustomer;
use App\Nova\Filters\ReceiptFromDate;
use App\Nova\Filters\ReceiptToDate;
use App\Nova\Lenses\ar\ArReceiptReport;
use Laravel\Nova\Fields\Date;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\ActionRequest;
use Laravel\Nova\Http\Requests\NovaRequest;

class Receipt_ar extends Resource
{

    public static $group = '9.1 งานลูกหนี้การค้า';
    public static $priority = 4;
    public static $polling = true;
    public static $pollingInterval = 90;
    public static $showPollingToggle = false;
    public static $globallySearchable = false;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Receipt_ar::class;

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
        return 'ใบเสร็จรับเงินวางบิล';
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
            Boolean::make('สถานะ', 'status')
                ->readonly(),
            Text::make('เลขที่ใบเสร็จรับเงิน', 'receipt_no'),
            Date::make('วันที่', 'receipt_date'),

            Select::make('ประเภทใบเสร็จ', 'receipttype')->options([
                'H' => 'ต้นทาง',
                'B' => 'วางบิล',
                'E' => 'ปลายทาง'
            ])->displayUsingLabels()
                ->default('B')
                ->readonly(),
            BelongsTo::make(__('Branch'), 'branch', 'App\Nova\Branch'),

            BelongsTo::make('ลูกค้า', 'ar_customer', 'App\Nova\Ar_customer')
                ->searchable(),
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
                ->withSubtitles()
                ->nullable(),
            Text::make('เลขที่รายการโอน', 'bankreference')
                ->hideFromIndex(),
            Text::make('เช็คเลขที่', 'chequeno')
                ->hideFromIndex(),
            BelongsTo::make('เช็คของธนาคาร', 'chequebank', 'App\Nova\Bank')
                ->hideFromIndex()
                ->nullable(),
            Text::make('รายละเอียดอื่นๆ/หมายเหตุ', 'description')
                ->hideFromIndex(),
            Currency::make('ส่วนลด', 'discount_amount')
                ->hideFromIndex(),
            Currency::make('ภาษี', 'tax_amount')
                ->hideFromIndex(),
            Currency::make('ยอดรับชำระ', 'pay_amount')
                ->hideFromIndex(),
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
            new ReceiptByCustomer,
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
        return [
            new ArReceiptReport(),
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
            (new PrintReceipt())
                ->canRun(function ($request) {
                    return  $request->user()->hasPermissionTo('view receipt_ar');
                })
                ->canSee(function ($request) {
                    return  $request->user()->hasPermissionTo('view receipt_ar');
                }),
            (new CancelReceipt())
                ->onlyOnDetail()
                ->confirmText('ต้องการยกเลิกใบเสร็จรับเงินรายการนี้?')
                ->confirmButtonText('ยกเลิก')
                ->cancelButtonText("ไม่ยกเลิก")
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit receipt_ar');
                })
                ->canSee(function ($request) {
                    return $request instanceof ActionRequest
                        || ($this->resource->exists && ($this->resource->status == true
                            && $request->user()->hasPermissionTo('edit receipt_ar')));
                }),

        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('receipttype', '=', 'B');
    }
}
