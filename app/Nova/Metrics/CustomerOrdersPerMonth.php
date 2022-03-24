<?php

namespace App\Nova\Metrics;

use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Trend;
use App\Models\Order_header;

class CustomerOrdersPerMonth extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {

        return $this->sumByMonths($request, Order_header::whereNotIn('order_status', ['checking', 'new', 'cancel'])
            ->where('customer_id', $request->resourceId), 'order_amount')
            ->showSumValue()
            ->format('0,0.00');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            3 => '3 เดือน',
            6 => '6 เดือน',
            9 => '9 เดือน',
            12 => '12 เดือน',
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

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'customer-orders-per-month';
    }
    public function name()
    {
        return 'ยอดค่าขนส่งตามผู้ส่งรายเดือน';
    }
}
