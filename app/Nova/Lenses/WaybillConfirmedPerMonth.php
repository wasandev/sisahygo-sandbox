<?php

namespace App\Nova\Lenses;

use App\Models\Car;
use App\Models\Cartype;
use App\Nova\Actions\Accounts\PrintWaybillConfirmPerMonth;
use App\Nova\Filters\CarType as FiltersCarType;
use App\Nova\Filters\Lenses\WaybillLensFromDate;
use App\Nova\Filters\Lenses\WaybillLensToDate;
use App\Nova\Filters\RouteToBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class WaybillConfirmedPerMonth extends Lens
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
                ->join('cars', 'cars.id', '=', 'waybills.car_id')
                ->join('cartypes', 'cars.cartype_id', '=', 'cartypes.id')
                ->join('branches', 'branches.id', '=', 'waybills.branch_rec_id')
                ->whereNotIn('waybills.waybill_status', ['loading', 'cancel'])
                ->orderBy('waybills.departure_at', 'asc')
                ->orderBy('waybills.waybill_type', 'asc')
                ->orderBy('waybills.branch_rec_id', 'asc')
                ->groupBy('waybills.departure_at', 'waybills.waybill_date', 'waybills.waybill_type', 'waybills.branch_rec_id', 'waybills.car_id', 'cars.cartype_id', 'cartypes.name', 'cars.car_regist', 'waybills.waybill_no', 'waybills.id')
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
            'waybills.waybill_no',
            'waybills.departure_at',
            'waybills.waybill_date',
            'waybills.waybill_type',
            'branches.name as branch_rec',
            'waybills.car_id',
            'cars.cartype_id',
            'cartypes.name',
            'cars.car_regist',
            DB::raw('sum(waybills.waybill_amount) as amount'),
            DB::raw('sum(waybills.waybill_payable) as payable'),
            DB::raw('sum(waybills.waybill_income) as income'),
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
            Date::make('วันที่รถออก', 'departure_at')
                ->format('DD/MM/YYYY'),
            Date::make('วันที่ใบกำกับ', 'waybill_date')
                ->format('DD/MM/YYYY'),
            Text::make('ใบกำกับ', 'waybill_no'),
            Text::make('ประเภท', 'waybill_type', function () {
                if ($this->waybill_type === 'general') {
                    return 'เบ็ดเตล็ด';
                } elseif ($this->waybill_type === 'express') {
                    return 'Express';
                } else {
                    return 'เหมาคัน';
                }
            }),
            Text::make(__('To branch'), 'branch_rec'),

            Text::make(__('Car regist'), 'car_regist'),
            Currency::make(__('ค่าระวาง'), 'amount', function ($value) {
                return $value;
            }),
            Currency::make(__('ค่าจ้างรถ'), 'payable', function ($value) {
                return $value;
            }),
            Currency::make(__('รายได้บริษัท'), 'income', function ($value) {
                return $value;
            }),
            Text::make(__('Car type'), 'name'),
            Number::make('สัดส่วนรายได้%', 'rate', function () {
                if (isset($this->income) && $this->amount > 0) {
                    return number_format(($this->income / $this->amount) * 100, 2, '.', ',');
                }
            })
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
            new RouteToBranch(),
            new FiltersCarType(),
            new WaybillLensFromDate(),
            new WaybillLensToDate()
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
            (new PrintWaybillConfirmPerMonth($request->filters))
                ->standalone()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view waybills');
                }),
                
            (new DownloadExcel)->withHeadings()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view waybills');
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
        return 'waybill-confirmed-per-month';
    }
    public function name()
    {
        return 'รายงานรถออกประจำเดือน';
    }
}
