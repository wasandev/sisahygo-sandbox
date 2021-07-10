<?php

namespace App\Nova\Lenses\accounts;

use App\Nova\Actions\Accounts\PrintOrderBillingByUser;
use App\Nova\Filters\Branch;
use App\Nova\Filters\LensBranchFilter;
use App\Nova\Filters\OrderdateFilter;
use App\Nova\Filters\OrderFromDate;
use App\Nova\Filters\OrderToDate;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Date;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class OrderBillingByUser extends Lens
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
                ->join('branches', 'branches.id', '=', 'order_headers.branch_id')
                ->join('users', 'users.id', '=', 'order_headers.user_id')
                ->whereNotIn('order_headers.order_status', ['checking', 'new'])
                ->orderBy('ordercount', 'desc')
                ->orderBy('order_headers.order_header_date', 'asc')
                ->groupBy('order_headers.branch_id', 'order_headers.user_id', 'order_headers.order_header_date')
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
            'branches.name as branch_name',
            'users.name as user_name',
            'order_headers.order_header_date',
            DB::raw('count(order_headers.id) as ordercount'),
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

            Text::make(__('Branch'), 'branch_name'),
            Text::make('ชื่อพนักงาน', 'user_name'),
            Date::make(__('Order date'), 'order_header_date')
                ->format('DD/MM/YYYY'),
            Number::make(__('จำนวนใบรับส่ง'), 'ordercount', function ($value) {
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
            new LensBranchFilter(),
            new OrderdateFilter(),
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
            // (new PrintOrderBillingByUser($request->filters))
            //     ->standalone()
            //     ->canSee(function ($request) {
            //         return $request->user()->hasPermissionTo('view order_headers');
            //     }),
            (new DownloadExcel)->allFields()
                ->withHeadings()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view order_headers');
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
        return 'order-billing-by-user';
    }
    public function name()
    {
        return 'รายงานจำนวนใบรับส่งตามพนักงาน';
    }
}
