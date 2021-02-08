<?php

namespace App\Nova\Metrics;

use App\Models\Category;
use App\Models\Product;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class ProductByCategory extends Partition
{
    public function name()
    {
        return 'สินค้าตามกลุ่ม';
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Product::class,  'category_id')
            ->label(function ($value) {
                $category = Category::find($value);
                if (isset($category)) {
                    switch ($category->name) {
                        case null:
                            return 'None';
                        default:
                            return ucfirst($category->name);
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
        return 'product-by-category';
    }
}
