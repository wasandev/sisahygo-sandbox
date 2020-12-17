<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ByWaybill extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';
    public $name = 'ใบกำกับ';

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
        return $query->where('waybill_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $branch = \App\Models\Branch::find($request->user()->branch_id);
        $routeto_branch = \App\Models\Routeto_branch::where('dest_branch_id', '=', $branch->id)->get('id');
        $waybills = \App\Models\Waybill::whereIn('routeto_branch_id', $routeto_branch);
        return $waybills->pluck('id', 'waybill_no')->all();
    }
}
