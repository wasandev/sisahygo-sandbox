<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class EmployeeType extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';
    public $name = 'ประเภทพนักงาน';

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
        return $query->where('type', $value);
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
            'ผู้บริหาร' => 'ผู้บริหาร',
            'พนักงานบริษัท' => 'พนักงานบริษัท',
            'พนักงานบริษัทร่วม' => 'พนักงานบริษัทร่วม',
            'แรงงาน' => 'แรงงาน',
            'พนักงานขับรถบริษัท' => 'พนักงานขับรถบริษัท',
            'พนักงานขับรถร่วม' => 'พนักงานขับรถร่วม',
        ];
    }
}
