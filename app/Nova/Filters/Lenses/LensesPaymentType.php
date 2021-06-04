<?php

namespace App\Nova\Filters\Lenses;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;

class LensesPaymentType extends BooleanFilter
{
    public $name = 'กรณีเก็บเงินปลายทาง';
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
        if ($value['typeb']) {
            return $query->where('carpayments.type', 'B');
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
            'กรณีเก็บปลายทาง' => 'typeb',
        ];
    }
}
