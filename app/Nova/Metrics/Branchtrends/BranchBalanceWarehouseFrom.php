<?php

namespace App\Nova\Metrics\Branchtrends;

use App\Models\Branchrec_order;
use App\Models\Order_header;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class BranchBalanceWarehouseFrom extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {

        //return $this->count($request, Book::join(‘categories’, ’books . category_id’, ’ = ’, ’categories . id’), ‘categories . name’);
        return $this->sumByDays($request, Branchrec_order::where('order_status', 'branch warehouse')
            ->where('branch_rec_id', $request->resourceId), 'order_amount', 'updated_at')
            ->format('0,0.00')
            ->showSumValue();
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [

            30 => '30 วัน',
            60 => '60 วัน',
            365 => '365 วัน',
        ];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }
    public function name()
    {
        return 'ยอดค่าขนส่งสินค้าค้างส่งของสาขา';
    }
    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'branchs-branch-balance-warehouse-from';
    }
}
