<?php

namespace App\Nova\Filters;

use App\Models\Branch_area;
use App\Models\Productservice_price;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;


class ProductNotinPrice extends  BooleanFilter
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
        if ($value['notinzone']) {
            $districts = array();
            $branch_areas = Branch_area::where('branch_id', '<>', 1)->get();


            foreach ($branch_areas as $branch_area) {
                $districts[] = array_push($districts, $branch_area->district);
            }
            return $query->leftjoin('productservice_price', 'productservice_price.product_id', '=', 'products.id')
                ->whereNotIn('productservice_price.district', $districts);
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
            'สินค้าที่ไม่มีตารางราคา' => 'notinzone'
        ];
    }
}
