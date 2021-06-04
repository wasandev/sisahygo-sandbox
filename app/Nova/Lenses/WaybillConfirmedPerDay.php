<?php

namespace App\Nova\Lenses;

use App\Models\Car;
use App\Models\Cartype;
use App\Nova\Actions\Accounts\PrintWaybillConfirmPerDay;
use App\Nova\Filters\Lenses\WaybillLensFromDate;
use App\Nova\Filters\Lenses\WaybillLensToDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

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
                ->join('branches', 'branches.id', '=', 'waybills.branch_rec_id')
                ->whereNotIn('waybills.waybill_status', ['loading', 'cancel'])
                ->orderBy('waybills.waybill_date', 'asc')
                ->orderBy('waybills.waybill_type', 'asc')
                ->orderBy('waybills.branch_rec_id', 'asc')
                ->groupBy('waybills.waybill_date', 'waybills.waybill_type', 'waybills.branch_rec_id', 'waybills.car_id', 'cars.cartype_id')
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
            'waybills.waybill_date',
            'waybills.waybill_type',
            'branches.name as branch_rec',
            'waybills.car_id',
            'cars.cartype_id',
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
            Text::make(__('Car type'), function () {
                $cartype = Cartype::find($this->cartype_id);
                return $cartype->name;
            }),
            Text::make(__('Car regist'), function () {

                return $this->car->car_regist;
            }),
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
            (new PrintWaybillConfirmPerDay($request->filters))
                ->standalone()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view waybills');
                }),
            (new DownloadExcel)->allFields()->withHeadings()
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
        return 'waybill-confirmed-per-day';
    }
    public function name()
    {
        return 'รายงานรถออกประจำวัน';
    }
}
