<?php

namespace App\Nova\Filters\Accounts;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ArType extends Filter
{
    public $name = 'ประเภทขายเชื่อ';
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

        return $query->where('order_headers.paymenttype', $value);
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

            'วางบิลต้นทาง' => 'F',
            'วางบิลปลายทาง' => 'L',
            'เก็บเงินปลายทาง' => 'E',
        ];
    }
}
