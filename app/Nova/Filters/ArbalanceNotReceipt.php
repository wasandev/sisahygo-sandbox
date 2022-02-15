<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;

class ArbalanceNotReceipt extends BooleanFilter
{
    public $name = 'ค้างชำระ';
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
        if ($value['notpay']) {
            return $query->where('receipt_id', '=', null);
        } else {
            return $query->where('receipt_id', '<>', null);
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
            'ค้างชำระ' => 'notpay',
            'ชำระแล้ว' => 'payed'
        ];
    }
}
