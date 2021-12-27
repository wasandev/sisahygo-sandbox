<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class CheckerByUser extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';
    public $name = 'ตามพนักงานตรวจสินค้า';

    public function default()
    {
        if (auth()->user()->role == 'admin') {
            return '';
        } else {
            return auth()->user()->id;
        }
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
        return $query->where('checker_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $users = \App\Models\User::has('order_checkers');
        return $users->pluck('id', 'name')->all();
    }
}
