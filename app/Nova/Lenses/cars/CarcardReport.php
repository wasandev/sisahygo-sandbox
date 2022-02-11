<?php

namespace App\Nova\Lenses\cars;

use App\Nova\Actions\Accounts\PrintCarCardReportByDay;
use App\Nova\Filters\CarbalanceByCar;
use App\Nova\Filters\CarbalanceByOwner;
use App\Nova\Filters\CarbalanceFromDate;
use App\Nova\Filters\CarbalanceToDate;
use App\Nova\Filters\CarcardByOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
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
                ->join('car_balances', 'car_balances.car_id', '=', 'cars.id')
                ->orderBy('cars.vendor_id', 'asc')
                ->orderBy('cars.id', 'asc')
                ->groupBy('cars.vendor_id', 'cars.id')


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
            'cars.id',
            'cars.vendor_id',
            'cars.car_regist',
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
            ID::make('id'),
            BelongsTo::make('เจ้าของรถ', 'owner', 'App\Nova\Vendor'),
            Text::make('ทะเบียนรถ', 'car_regist'),
            Currency::make('ยอดรับ', 'recamount'),
            Currency::make('ยอดจ่าย', 'payamount'),
            Number::make('คงเหลือ', function () {
                return number_format($this->recamount - $this->payamount, 2, '.', ',');
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
            new CarcardByOwner(),
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
