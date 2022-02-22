<?php

namespace App\Nova\Lenses\accounts;

use App\Nova\Actions\Accounts\PrintPaymentReportByDay;
use App\Nova\Filters\Lenses\PaymentLensFromDate;
use App\Nova\Filters\Lenses\PaymentLensToDate;
use App\Nova\Filters\Lenses\LensesPaymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                ->join('carpayments', 'carpayments.car_id', '=', 'cars.id')
                ->join('vendors', 'vendors.id', '=', 'carpayments.vendor_id')
                ->where('carpayments.status', true)
                ->orderBy('amount', 'desc')
                ->groupBy('vendors.id', 'vendors.name', 'cars.id', 'cars.car_regist')
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
            'vendors.id',
            'vendors.name',
            'cars.id',
            'cars.car_regist',

            DB::raw('sum(carpayments.amount) as amount'),


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
            Text::make('จ่ายให้', 'name'),
            Text::make('ทะเบียนรถ', 'car_regist'),
            Currency::make('จำนวนเงิน', 'amount'),



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
