<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class LoaderShowByOrderStatus extends Filter
{

    public $name = 'ตามสถานะ';

    // public function default()
    // {
    //     return 'confirmed';
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
        return $query->where('order_status', $value);
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
            'ตรวจรับสินค้าแล้ว' => 'new',
            'รับสินค้าไว้แล้ว' => 'confirmed',
            'จัดขึ้นรถแล้ว' => 'loaded',
            'อยู่ระหว่างขนส่งไปสาขา' => 'in transit',
            'สินค้าอยู่สาขาปลายทางแล้ว' => 'arrival',
            'สินค้าอยู่คลังสาขา รอการจัดส่ง' => 'branch warehouse',
            'สินค้าอยู่ระหว่างจัดส่ง' => 'delivery',
            'สินค้าจัดส่งถึงผู้รับปลายทางแล้ว' => 'completed',
            'ยกเลิกรายการแล้ว' => 'cancel',
            'มีปัญหาการขนส่ง' => 'problem'
        ];
    }
}
