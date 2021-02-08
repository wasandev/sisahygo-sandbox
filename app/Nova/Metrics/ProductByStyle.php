<?php

namespace App\Nova\Metrics;

use App\Models\Product_style;
use App\Models\Product;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class ProductByStyle extends Partition
{
    public function name()
    {
        return 'สินค้าตามประเภท';
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Product::class,  'product_style_id')
            ->label(function ($value) {
                $product_style = Product_style::find($value);
                if (isset($product_style)) {
                    switch ($product_style->name) {
                        case null:
                            return 'None';
                        default:
                            return ucfirst($product_style->name);
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
        return 'product-by-style';
    }
}
