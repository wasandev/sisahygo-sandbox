<?php

namespace App\Nova\Filters;


use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class WhtaxType extends Filter
{

    public $name = 'ตามประเภท ภงด.';


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
        return $query->select('withholdingtaxes.*')
            ->join('incometypes', 'incometypes.id', '=', 'withholdingtaxes.incometype_id')
            ->where('incometypes.taxform', $value);
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

            'ภ.ง.ด.3' => '3',
            'ภ.ง.ด.53' => '53'
        ];
    }
}
