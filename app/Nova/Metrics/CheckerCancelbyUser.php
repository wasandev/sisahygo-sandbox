<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use App\Models\Order_checker;

class CheckerCancelbyUser extends Value
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Order_checker::where('checker_id', $request->user()->id)
            ->where('order_status', '=', 'cancel'))
            ->format('0,0');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            'TODAY' => 'วันนี้',
            'MTD' => 'เดือนนี้',
            'QTD' => 'ไตรมาสนี้',
            'YTD' => 'ปีนี้',
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
        return 'จำนวนรายการตรวจรับสินค้าที่ยกเลิก';
    }
    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'checker-cancelby-user';
    }
}
