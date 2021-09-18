<?php

namespace App\Nova;

use App\Nova\Actions\PrintReceipt;
use App\Nova\Filters\Branch;
use App\Nova\Filters\ReceiptFromDate;
use App\Nova\Filters\ReceiptToDate;
use Laravel\Nova\Fields\Date;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Receipt extends Resource
{
    public static $group = '8.สำหรับสาขา';
    public static $priority = 6;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Receipt::class;


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
        return 'ใบเสร็จรับเงินปลายทาง';
    }
    public static function availableForNavigation(Request $request)
    {
        return $request->user()->hasPermissionTo('edit receipts');
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
            ])->displayUsingLabels()
                ->readonly(),
            BelongsTo::make(__('Branch'), 'branch', 'App\Nova\Branch'),

            BelongsTo::make('ลูกค้า', 'customer', 'App\Nova\Customer'),
            Currency::make('จำนวนเงิน', 'total_amount'),
            Select::make('ชำระโดย', 'branchpay_by')->options([
                'C' => 'เงินสด',
                'T' => 'เงินโอน',
                'Q' => 'เช็ค',
                'R' => 'บัตรเครดิต'
            ])->displayUsingLabels()
                ->hideFromIndex(),
            Currency::make('ส่วนลด', 'discount_amount')
                ->hideFromIndex(),
            Currency::make('ภาษี', 'tax_amount')
                ->hideFromIndex(),
            Currency::make('ยอดรับชำระ', 'pay_amount')
                ->hideFromIndex(),
            HasOne::make('รายการจัดส่ง', 'delivery_item', 'App\Nova\Delivery_item')

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
            new Branch(),
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

    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($request->user()->branch->code <> '001') {
            return $query->where('receipttype', '=', 'E')
                ->where('branch_id', '=', $request->user()->branch_id);
        } else {
            return $query->where('receipttype', '=', 'E');
        }
    }
}
