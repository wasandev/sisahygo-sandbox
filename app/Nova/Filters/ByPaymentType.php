<?php

namespace App\Nova\Filters;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ByPaymentType extends Filter
{
    public $name = 'ประเภทการชำระเงิน';
    /**
     * The filter's component.
     *
     * @var string
     */

    public $component = 'select-filter';
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
        return $query->where('paymenttype', $value);
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
            'เงินสดต้นทาง' => 'H',
            'เงินโอนต้นทาง' => 'T',
            'เก็บสดปลายทาง' => 'E',
            'วางบิลต้นทาง' => 'F',
            'วางบิลปลายทาง' => 'L'
        ];
    }
}
