<?php

namespace App\Nova\Metrics;

use App\Models\Waybill;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Value;

class WaybillLoading extends Value
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        return $this->count($request, Waybill::loading());
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

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'waybill-loading';
    }
    public function name()
    {
        return 'จำนวนรถกำลังจัดขึ้น';
    }
}
