<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;

class ArbalanceNotInvoice extends BooleanFilter
{
    public $name = 'การออกใบแจ้งหนี้';
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
        if ($value['noinvoice']) {
            return $query->where('invoice_id', '=', null);
        } else {
            return $query->where('invoice_id', '<>', null);
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
        return [
            'ยังไม่ออกใบแจ้งหนี้' => 'noinvoice',
            'ออกใบแจ้งหนี้แล้ว' => 'invoiced'
        ];
    }
}
