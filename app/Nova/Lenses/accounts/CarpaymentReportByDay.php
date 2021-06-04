<?php

namespace App\Nova\Lenses\accounts;

use App\Nova\Actions\Accounts\PrintPaymentReportByDay;
use App\Nova\Filters\Lenses\PaymentLensFromDate;
use App\Nova\Filters\Lenses\PaymentLensToDate;
use App\Nova\Filters\Lenses\LensesPaymentType;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class CarpaymentReportByDay extends Lens
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
                ->join('cars', 'cars.id', '=', 'carpayments.car_id')
                ->join('vendors', 'vendors.id', '=', 'carpayments.vendor_id')
                ->where('carpayments.status', true)
                ->orderBy('carpayments.payment_date', 'asc')
                ->orderBy('carpayments.id', 'asc')
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
            'carpayments.id',
            'carpayments.payment_date',
            'carpayments.payment_no',
            'carpayments.vendor_id',
            'carpayments.car_id',
            'vendors.name as owner',
            'cars.car_regist',
            'carpayments.payment_by',
            'carpayments.amount',
            'carpayments.type'
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
            ID::make(__('ID'), 'id')->sortable(),
            Date::make('วันที่', 'payment_date'),
            Text::make('เลขที่', 'payment_no'),
            BelongsTo::make('จ่ายให้', 'vendor', 'App\Nova\Vendor'),
            BelongsTo::make('ทะเบียนรถ', 'car', 'App\Nova\Car'),
            Currency::make('จำนวนเงิน', 'amount'),
            Select::make('จ่ายด้วย', 'payment_by')
                ->options([
                    "H" => "เงินสด",
                    'T' => "เงินโอน",
                    'Q' => "เช็ค",
                    'A' => "รายการตัดบัญชี"
                ])->displayUsingLabels(),
            Select::make('ประเภท', 'type')
                ->options([
                    "T" => "ค่าบรรทุก",
                    'O' => "อื่นๆ",
                    'B' => "เก็บปลายทาง"
                ])->displayUsingLabels()


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
            new LensesPaymentType(),
            new PaymentLensFromDate(),
            new PaymentLensToDate(),
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
            (new PrintPaymentReportByDay($request->filters))
                ->standalone()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view car_payments');
                }),
            (new DownloadExcel)->allFields()->withHeadings()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view car_payments');
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
        return 'accounts-carpayment-report-by-day';
    }
    public function name()
    {
        return 'รายงานสรุปการจ่ายเงินรถ';
    }
}
