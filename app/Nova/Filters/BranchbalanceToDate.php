<?php

namespace App\Nova\Filters;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\DateFilter;

class BranchbalanceToDate extends DateFilter
{
    public $name = 'วันที่ตั้งหนี้ - ถึงวันที่';
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
        return $query->where('branchbal_date', '<=', Carbon::parse($value));
    }
}
