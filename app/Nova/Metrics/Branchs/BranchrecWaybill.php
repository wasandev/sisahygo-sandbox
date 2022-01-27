<?php

namespace App\Nova\Metrics\Branchs;

use App\Models\Branchrec_waybill;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class BranchrecWaybill extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->countByDays($request, Branchrec_waybill::whereNotIn('waybill_status', ['loading', 'confirmed', 'in transit'])
            ->where('branch_rec_id', $request->user()->branch_id), 'arrivaled_at')
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
        return 'จำนวนเที่ยวรถเข้าสาขา';
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'branchs-branchrec-waybill';
    }
}
