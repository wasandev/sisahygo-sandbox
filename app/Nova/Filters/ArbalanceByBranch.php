<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ArbalanceByBranch extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';
    public $name = 'เลือกสาขาวางบิล';
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
        $branch = \App\Models\Branch::find($value);
        if ($branch->id <> 1) {
            return $query->join('order_headers', 'order_headers.id', '=', 'ar_balances.order_header_id')
                ->where('order_headers.branch_rec_id', $branch->id)
                ->where('order_headers.paymenttype', '=', 'L');
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
        $branches = \App\Models\Branch::all();
        return $branches->pluck('id', 'name')->all();
    }
}
