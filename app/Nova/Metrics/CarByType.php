<?php

namespace App\Nova\Metrics;

use App\Models\Car;
use App\Models\Cartype;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class CarByType extends Partition
{
    public function name()
    {
        return 'จำนวนรถตามประเภท';
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Car::class,  'cartype_id')
            ->label(function ($value) {
                $cartype = Cartype::find($value);
                switch ($cartype->name) {
                    case null:
                        return 'None';
                    default:
                        return ucfirst($cartype->name);
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
        return 'car-by-type';
    }
}
