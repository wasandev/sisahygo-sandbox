<?php

namespace App\Nova\Lenses;

use App\Nova\Filters\OrderFromDate;
use App\Nova\Filters\OrderToDate;
use App\Nova\Filters\ToBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date as FieldsDate;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;

class BranchTranDays extends Lens
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
                ->join('order_statuses', 'order_headers.id', '=', 'order_statuses.order_header_id') 
                ->join('branches', 'order_headers.branch_rec_id', '=', 'branches.id') 
                ->join('customers', 'order_headers.customer_rec_id', '=', 'customers.id')                  
                ->where('order_headers.order_status','=','completed')
                ->where('order_statuses.status','=','completed')                
                ->orderBy('branches.id', 'desc')
                ->groupBy('branches.id','customers.district')
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
            'branches.name as branch_name',
            'customers.district as district',      
            DB::raw('AVG(DATEDIFF(order_statuses.created_at,order_headers.order_header_date))  as trandaysavg'),
                 
            
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
           
            Text::make('สาขา', 'branch_name'),
            Text::make('อำเภอ', 'district'),
            
            Number::make('จำนวนวันเฉลี่ย', 'trandaysavg', function ($value) {
                return $value;
            })->min(0),
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

            new OrderFromDate,
            new OrderToDate,
            new ToBranch,
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

        ];
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'branch-tran-days';
    }


    public function name()
    {
        return 'เวลาการจัดส่งตามสาขาปลายทาง';
    }
}
