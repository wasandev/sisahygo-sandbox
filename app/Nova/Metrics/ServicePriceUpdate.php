<?php

namespace App\Nova\Metrics;

use App\Models\Productservice_newprice;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use App\Models\Productservice_newprice3;
use Illuminate\Support\Facades\DB;

class ServicePriceUpdate extends Partition
{
    public function name()
    {
        return '%การปรับราคา';
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Productservice_newprice::whereMonth('updated_at', 2), 'id', 'updated_at');
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
        return 'service-price-update';
    }
}
