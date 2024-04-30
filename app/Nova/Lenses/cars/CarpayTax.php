<?php

namespace App\Nova\Lenses\cars;


use App\Nova\Actions\Accounts\PrintCarwhtaxReport;
use App\Nova\Actions\PostWhTax;
use App\Nova\Filters\CarpaymentFromDate;
use App\Nova\Filters\CarpaymentToDate;
use App\Nova\Filters\Lenses\LensesPaymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class CarpayTax extends Lens
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
                ->join('carpayments', 'vendors.id', '=', 'carpayments.vendor_id')
                ->where('carpayments.status', '=', true)
                ->where('carpayments.tax_flag', '=', true)
                ->orderBy('vendors.id', 'asc')
                ->groupBy('vendors.id')


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
            'vendors.name as name',
            DB::raw('sum(carpayments.amount) as payment_amount'),
            DB::raw('sum(carpayments.tax_amount) as tax_amount'),
            DB::raw("SUM(CASE WHEN carpayments.type <> 'B' THEN carpayments.amount ELSE 0 END) as pay1_amount"),
            DB::raw("SUM(CASE WHEN carpayments.type <> 'B' THEN carpayments.tax_amount ELSE 0 END) as tax1_amount"),
            DB::raw("SUM(CASE WHEN carpayments.type = 'B' THEN carpayments.amount ELSE 0 END) as pay2_amount"),
            DB::raw("SUM(CASE WHEN carpayments.type = 'B' THEN carpayments.tax_amount ELSE 0 END) as tax2_amount"),



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

            ID::make(),
            Text::make('เจ้าของรถ', 'name'),
            Currency::make('จำนวนเงินจ่ายรวม', 'payment_amount', function () {
                return $this->payment_amount;
            }),
            Currency::make('จำนวนเงินภาษีรวม', 'tax_amount', function () {
                return $this->tax_amount;
            }),
            Currency::make('จำนวนเงินจ่ายต้นทาง', 'pay1_amount', function () {
                return $this->pay1_amount;
            }),
            Currency::make('จำนวนเงินภาษีต้นทาง', 'tax1_amount', function () {
                return $this->tax1_amount;
            }),
            Currency::make('จำนวนเงินจ่ายปลายทาง', 'pay2_amount', function () {
                return $this->pay2_amount;
            }),
            Number::make('จำนวนเงินภาษีปลายทาง', 'tax2_amount', function () {
                return number_format($this->tax2_amount, 2, '.', ',');
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
            new LensesPaymentType(),
            new CarpaymentFromDate(),
            new CarpaymentToDate()
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
            (new PrintCarwhtaxReport($request->filters))
                ->standalone()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view car_balances');
                }),
            (new PostWhTax($request->filters))
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('create withholdingtaxes');
                }),
            (new DownloadExcel)->allFields()
                ->withHeadings()
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
        return 'carpay-tax';
    }
    public function name()
    {
        return 'ภาษีหัก ณ ที่จ่าย(รถ)';
    }
}
