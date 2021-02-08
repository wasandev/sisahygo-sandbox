<?php

namespace App\Nova\Metrics;

use App\Models\Unit;
use App\Models\Product;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class ProductByUnit extends Partition
{
    public function name()
    {
        return 'สินค้าตามหน่วยนับ';
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Product::class,  'unit_id')
            ->label(function ($value) {
                $unit = Unit::find($value);
                if (isset($unit)) {
                    switch ($unit->name) {
                        case null:
                            return 'None';
                        default:
                            return ucfirst($unit->name);
                    }
                }
            });
    }



    /**
     * Get the URI key for the metric.
     *
     * @return string
     */

    public function uriKey()
    {
        return 'product-by-unit';
    }
}
