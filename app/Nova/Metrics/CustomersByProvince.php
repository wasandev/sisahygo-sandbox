<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use App\Models\Customer;

class CustomersByProvince extends Partition
{
    public function name()
    {
        return 'จำนวนลูกค้าตามจังหวัด';
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Customer::class, 'province');
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
        return 'customers-by-province';
    }
}
