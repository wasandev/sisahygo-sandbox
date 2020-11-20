<?php

namespace App\Observers;

use App\Models\Order_status;

class Order_statusObserver
{
    public function creating(Order_status $order_status)
    {
        $order_status->user_id = auth()->user()->id;
    }

    public function updating(Order_status $order_status)
    {
        $order_status->updated_by = auth()->user()->id;
    }
}
