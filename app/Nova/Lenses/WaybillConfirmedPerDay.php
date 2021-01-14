<?php

namespace App\Nova\Lenses;

use App\Models\Routeto_branch;
use App\Nova\Actions\WaybillByDatePrint;
use App\Nova\Filters\WaybillFromDate;
use App\Nova\Filters\WaybillToDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;

class WaybillConfirmedPerDay extends Lens
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
                //->join('cartypes', 'cars.cartype_id', '=', 'cars.car_id')
                ->join('routeto_branch', 'routeto_branch.id', '=', 'waybills.routeto_branch_id')
                ->whereNotIn('waybills.waybill_status', ['loading', 'cancel'])
                ->orderBy('waybills.routeto_branch_id', 'asc')
                ->groupBy('waybills.routeto_branch_id', 'waybills.waybill_type', 'cars.cartype_id', 'waybills.id')
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
            'routeto_branch.name',
            'waybills.waybill_type',
            'waybills.waybill_date',
            'waybills.routeto_branch_id',
            'cars.cartype_id',
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
            // ID::make(__('ID'), 'id')->sortable(),
            Date::make('วันที่', 'waybill_date')
                ->format('DD/MM/YYYY'),
            Text::make(__('To branch'), 'tobranch', function () {
                $routetobranch = \App\Models\Routeto_branch::find($this->routeto_branch_id);
                $tobranch = \App\Models\Branch::find($routetobranch->dest_branch_id);
                return $tobranch->name;
            }),
            Text::make('ประเภท', 'waybill_type', function () {
                if ($this->waybill_type === 'general') {
                    return 'เบ็ดเตล็ด';
                } elseif ($this->waybill_type === 'express') {
                    return 'Express';
                } else {
                    return 'เหมาคัน';
                }
            }),
            Text::make(__('Car type'), 'cartype', function () {
                $cartype = \App\Models\Cartype::find($this->cartype_id);
                return $cartype->name;
            }),
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
            Number::make('สัดส่วนรายได้%', 'rate', function () {
                return number_format(($this->income / $this->amount) * 100, 2, '.', ',');
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
            WaybillByDatePrint::make()->standalone(),
        ];
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'waybill-confirmed-per-day';
    }
    public function name()
    {
        return 'ข้อมูลรถออกตามวัน';
    }
}
