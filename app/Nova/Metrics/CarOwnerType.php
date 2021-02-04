<?php

namespace App\Nova\Metrics;

use App\Models\Car;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class CarOwnerType extends Partition
{
    public function name()
    {
        return 'จำนวนรถตามการเป็นเจ้าของ';
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Car::class,  'ownertype')
            ->label(function ($value) {
                switch ($value) {
                    case 'owner':
                        return 'รถบริษัท';
                    case 'partner':
                        return 'รถร่วมบริการ';
                }
            });
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

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'car-owner-type';
    }
}
