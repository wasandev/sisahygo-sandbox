<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Nova\Filters\DateFilter;

class ReceiptToDate extends DateFilter
{
    public $name = 'ถึงวันที่';
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function default()
    {
        return date(today());
    }
    public function apply(Request $request, $query, $value)
    {
        return $query->whereDate('receipt_date', '<=', $value);
    }
}
