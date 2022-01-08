<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ShowByWaybillStatus extends Filter
{

    public $name = 'ตามสถานะ';

    // public function default()
    // {
    //     return 'new';
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
        return $query->where('waybill_status', $value);
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
            'กำลังจัดขึ้นสินค้า' => 'loading',
            'ยืนยันแล้ว' => 'confirmed',
            'ออกจากสาขาต้นทางแล้ว' => 'in transit',
            'ถึงสาขาปลายทางแล้ว' => 'arrival',
            'อยู่ระหว่างจัดส่ง' => 'delivery',
            'จบงานแล้ว' => 'completed',
            'ยกเลิก' => 'cancel',
            'มีปัญหาการขนส่ง' => 'problem'
        ];
    }
}
