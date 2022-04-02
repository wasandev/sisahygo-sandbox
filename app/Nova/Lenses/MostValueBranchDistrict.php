<?php

namespace App\Nova\Lenses;

use App\Nova\Filters\BranchbalanceToDate;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Illuminate\Support\Facades\DB;
use App\Nova\Filters\OrderFromDate;
use App\Nova\Filters\OrderToDate;

class MostValueBranchDistrict extends Lens
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
                ->join('order_headers', 'branches.id', '=', 'order_headers.branch_rec_id')
                ->join('branch_areas', 'branches.id', '=', 'branch_areas.branch_id')
                //->where('branches.id', '=', $request->resourceId)
                ->orderBy('branches.id', 'desc')
                ->orderBy('district_amount', 'desc')
                ->groupBy('branches.id', 'branches.name', 'branch_areas.district')
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
            'branches.id',
            'branches.name',
            'branch_areas.district',
            DB::raw('sum(order_headers.order_amount) as district_amount'),
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
            Text::make('สาขา', 'name'),
            Text::make('อำเภอ', 'district'),
            Currency::make('ยอดค่าขนส่ง', 'district_amount', function ($value) {
                return $value;
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

            new OrderFromDate(),
            new OrderToDate()
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
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'most-value-branch-district';
    }
    public function name()
    {
        return 'ค่าขนส่งตามอำเภอของสาขา';
    }
}
