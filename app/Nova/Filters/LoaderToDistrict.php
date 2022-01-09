<?php

namespace App\Nova\Filters;

use App\Models\Customer;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class LoaderToDistrict extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';
    public $name = 'ตามอำเภอปลายทาง';



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
        return $query->join('customers', 'order_headers.customer_rec_id', '=', 'customers.id')->where('customers.district', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        if (isset(auth()->user()->branch_rec_id)) {
            $districts = \App\Models\District::whereHas('branch_area', function ($q) {
                $q->where('branch_id', auth()->user()->branch_rec_id);
            });
        } else {
            $districts = \App\Models\District::whereHas('branch_area');
        }


        return $districts->pluck('name', 'name')->all();
    }
}
