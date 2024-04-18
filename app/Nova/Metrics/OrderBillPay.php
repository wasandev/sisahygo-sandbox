<?php

namespace App\Nova\Metrics;

use App\Models\Receipt;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class OrderBillPay extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->sumByDays($request, Receipt::where('receipttype', 'B'), 'pay_amount', 'receipt_date')
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
        return now()->addMinutes(5);
    }
    public function name()
    {
        return 'ยอดรับชำระหนี้ลูกหนี้การค้า';
    }
    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'order-bill-pay';
    }
}
