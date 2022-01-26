<?php

namespace App\Nova\Lenses;

use App\Nova\Filters\ArbalanceToDate;
use App\Nova\Filters\OrderFromDate;
use App\Nova\Filters\OrderToDate;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Illuminate\Support\Facades\DB;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class MostValueableAr extends Lens
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
                ->join('ar_balances', 'customers.id', '=', 'ar_balances.customer_id')
                ->where('ar_balances.doctype', '=', 'P')
                ->orderBy('revenue', 'desc')
                ->groupBy('customers.id', 'customers.name')
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
            'customers.id',
            'customers.name',
            DB::raw('sum(ar_balances.ar_amount) as revenue'),
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
            Text::make(__('Name'), 'name'),
            Currency::make(__('Revenue'), 'revenue', function ($value) {
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
            new ArbalanceToDate,
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
            (new DownloadExcel)->allFields()->withHeadings()
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit ar_customer');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit ar_customer');
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
        return 'most-valueable-ar';
    }
    public function name()
    {
        return 'ยอดค่าขนส่งวางบิลตามลูกค้า';
    }
}
