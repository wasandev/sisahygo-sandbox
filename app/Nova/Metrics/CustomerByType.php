<?php

namespace App\Nova\Metrics;

use App\Models\Customer;
use App\Models\Businesstype;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class CustomerByType extends Partition
{
    public function name()
    {
        return 'ลูกค้าตามประเภทธุรกิจ';
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Customer::class,  'businesstype_id')
            ->label(function ($value) {
                $businesstype = Businesstype::find($value);
                if (isset($businesstype)) {
                    switch ($businesstype->name) {
                        case null:
                            return 'None';
                        default:
                            return ucfirst($businesstype->name);
                    }
                }
            });
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    // public function cacheFor()
    // {
    //     return now()->addMinutes(5);
    // }


    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'customer-by-type';
    }
}
