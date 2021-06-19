<?php

namespace App\Nova\Lenses\cars;

use App\Nova\Actions\Accounts\PrintCarCardReportByDay;
use App\Nova\Filters\CarbalanceByCar;
use App\Nova\Filters\CarbalanceByOwner;
use App\Nova\Filters\CarbalanceFromDate;
use App\Nova\Filters\CarbalanceToDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class CarcardReport extends Lens
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
                ->join('cars', 'cars.id', '=', 'car_balances.car_id')
                ->join('vendors', 'vendors.id', '=', 'car_balances.vendor_id')
                ->orderBy('car_balances.car_id', 'asc')
                ->groupBy('car_balances.vendor_id', 'car_balances.car_id')


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
            'car_balances.vendor_id',
            'car_balances.car_id',
            DB::raw("SUM(CASE WHEN doctype = 'R' THEN car_balances.amount ELSE 0 END) as recamount"),
            DB::raw("SUM(CASE WHEN doctype = 'P' THEN car_balances.amount ELSE 0 END) as payamount"),

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
            BelongsTo::make('เจ้าของรถ', 'vendor', 'App\Nova\Vendor'),
            BelongsTo::make('ทะเบียนรถ', 'car', 'App\Nova\Car'),
            Currency::make('ยอดรับ', 'recamount'),
            Currency::make('ยอดจ่าย', 'payamount'),
            Currency::make('คงเหลือ', function () {
                return $this->recamount - $this->payamount;
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
            new CarbalanceByOwner,
            new CarbalanceByCar(),
            new CarbalanceFromDate(),
            new CarbalanceToDate()
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
            (new PrintCarCardReportByDay($request->filters))
                ->standalone()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view car_balances');
                }),
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
        return 'cars-carcard-report';
    }
    public function name()
    {
        return 'ทะเบียนคุมรถ';
    }
}
