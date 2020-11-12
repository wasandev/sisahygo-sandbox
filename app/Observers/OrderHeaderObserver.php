<?php

namespace App\Observers;

use App\Models\Order_header;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class OrderHeaderObserver
{
    public function creating(Order_header $order_header)
    {
        $order_header->order_status = 'New';
        $order_header->order_date = date("d-m-Y");
        $order_header->user_id = auth()->user()->id;
    }
    public function updating(Order_header $order_header)
    {
        if ($order_header->order_status == 'Confirmed' && is_null($order_header->order_header_no)) {
            $order_header_no = IdGenerator::generate(['table' => 'order_headers', 'field' => 'order_header_no', 'length' => 15, 'prefix' => date('Ymd')]);
            $order_header->order_header_no = $order_header_no;
        }
        $order_header->updated_by = auth()->user()->id;
    }
}
