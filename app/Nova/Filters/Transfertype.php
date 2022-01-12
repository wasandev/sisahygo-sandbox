<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class Transfertype extends Filter
{

    public $name = 'ประเภทการโอน';

    // public function default()
    // {
    //     return 'B';
    // }
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
        return $query->where('transfer_type', $value);
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
            'เงินโอนต้นทาง' => 'H',
            'เงินโอนปลายทาง' => 'E',
            'รับชำระหนี้' => 'B'
        ];
    }
}
