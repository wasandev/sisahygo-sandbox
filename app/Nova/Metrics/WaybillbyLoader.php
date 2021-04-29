<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

use App\Models\Waybill;

class WaybillbyLoader extends Value
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Waybill::where('loader_id', $request->user()->id)
            ->where('waybill_status', '<>', 'loading'));
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
        return 'จำนวนใบกำกับที่จัดขึ้นแล้วของคุณ';
    }
    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'waybill-by-loader';
    }
}
