<?php

namespace App\Nova\Lenses\cars;


use App\Nova\Filters\WaybillFromDate;
use App\Nova\Filters\WaybillToDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class CarCheck extends Lens
{
    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query->select(self::columns())
                ->join('waybills', 'waybills.car_id', '=', 'cars.id')
                ->join('carpayments', 'carpayments.car_id', '=', 'cars.id')
                ->join('car_balances', 'car_balances.docno', 'waybills.waybill_no')
                ->join('order_headers', 'order_headers.waybill_id', '=', 'waybills.id')
                ->orderBy('waybills.id', 'asc')
                ->groupBy('waybills.id', 'carpayments.id', 'car_balances.id')


        ));
    }
    /**
     * Get the columns that should be selected.
     *
     * @return array
     */
    protected static function columns()
    {
        return [
            'waybills.id',
            'waybills.waybill_no as waybill_no',
            'waybills.waybill_date',
            'waybills.waybill_payable as payable',
            'carpayments.id',
            'carpayments.payment_no as payno',
            'carpayments.amount as carpayamount',
            'car_balances.amount as bal_amount',
            DB::raw("SUM(CASE WHEN order_headers.paymenttype = 'E' THEN order_amount ELSE 0 END) as branch_amount"),

        ];
    }
    /**
     * Get the fields available to the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            // ID::make('id'),
            Text::make('ใบกำกับ', 'waybill_no'),
            Date::make('วันที่ใบกำกับ', 'waybill_date'),
            Currency::make('ค่าบรรทุก', 'payable'),
            Currency::make('บัญชีรถ', 'bal_amount'),
            Number::make('ผลต่างบัญชีรถ', function () {
                return number_format($this->payable - $this->bal_amount, 2, '.', ',');
            }),

            Currency::make('จ่ายปลายทาง', 'branch_amount'),
            Text::make('ใบสำคัญจ่าย', 'payno'),
            Currency::make('ยอดจ่าย', 'carpayamount'),
            Number::make('ผลต่างยอดเก็บปลายทาง', function () {
                return number_format($this->carpayamount - $this->branch_amount, 2, '.', ',');
            }),


        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [

            new WaybillFromDate(),
            new WaybillToDate()
        ];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [

            (new DownloadExcel)->allFields()
                ->withHeadings()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view car_balances');
                }),
        ];
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'car-check';
    }
    public function name()
    {
        return 'ตรวจสอบยอดค่าบรรทุก';
    }
}
