<?php

namespace App\Nova\Filters;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\DateFilter;

class CarbalanceFromDate extends DateFilter
{
    public $name = 'จากวันที่';
    /**
     * The filter's component.
     *
     * @var string
     */


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
        return $query->where('cardoc_date', '>=', Carbon::parse($value));
    }
}
