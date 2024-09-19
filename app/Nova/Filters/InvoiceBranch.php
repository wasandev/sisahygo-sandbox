<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class InvoiceBranch extends Filter
{

    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';
    public $name = 'สาขาที่ออกเอกสาร';

    public function default()
    {
        
        //return auth()->user()->branch_id;
    
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
        $user = \App\Models\User::where('branch_id',$value)->get('id');
        return $query->whereIn('user_id', $user);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $users = \App\Models\Branch::all();
        return $users->pluck('id', 'name')->all();
    }
}
