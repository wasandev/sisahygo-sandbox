<?php

namespace App\Observers;

use App\Models\Order_detail;

class OrderDetailObserver
{
    public function creating(Order_detail $order_detail)
    {
        $order_detail->user_id = auth()->user()->id;
    }
    public function updating(Order_detail $order_detail)
    {
        $order_detail->updated_by = auth()->user()->id;
    }
}
