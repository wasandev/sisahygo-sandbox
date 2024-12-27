<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class BybranchType extends Filter
{
    public $name = 'ประเภทสาขา';
    /**
     * The filter's component.
     *
     * @var string
     */

    public $component = 'select-filter';


    public function default()
{
    return [
        'สาขาของบริษัท' => 'owner'
    ];
}
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
        
            return $query->where('branches.type', $value);
        
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
            'สาขาของบริษัท' => 'owner',
            'สาขาร่วมบริการ' => 'partner'
        ];
    }
}
