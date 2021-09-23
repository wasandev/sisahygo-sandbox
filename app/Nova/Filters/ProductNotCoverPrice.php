<?php

namespace App\Nova\Filters;

use App\Models\Branch_area;
use App\Models\Productservice_price;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;


class ProductNotCoverPrice extends  BooleanFilter
{
    /**
     * The filter's component.
     *
     * @var string
     */

    public $name = 'เลือกเงื่อนไข';
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        if ($value['notcover']) {

            return $query->withCount('productservice_price')
                ->having('productservice_price_count', '<', 103);
        }
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'สินค้าที่มีราคาไม่ครบอำเภอ' => 'notcover'
        ];
    }
}
