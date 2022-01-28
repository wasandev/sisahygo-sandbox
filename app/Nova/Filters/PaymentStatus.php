<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;

class PaymentStatus extends BooleanFilter
{
    public $name = 'สถานะการชำระเงิน';
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
        if ($value['payed']) {
            return $query->where('payment_status', "=", true);
        }
        if ($value['notpay']) {
            return $query->where('payment_status', "=", false);
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
            'ชำระแล้ว' => 'payed',
            'ค้างชำระ' => 'notpay'

        ];
    }
}
