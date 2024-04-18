<?php

namespace App\Nova\Filters\Accounts;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;

class CancelFlag extends BooleanFilter
{
    public $name = 'ไม่รวมยกเลิก';
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
        if ($value['cancelflag']) {
            return $query->whereNotIn('order_headers.order_status', ['checking', 'new', 'cancel']);
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
            'ไม่รวมยกเลิก' => 'cancelflag',
        ];
    }
}
