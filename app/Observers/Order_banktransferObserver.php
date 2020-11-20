<?php

namespace App\Observers;

use App\Models\Order_banktransfer;
use App\Models\Order_header;

class Order_banktransferObserver
{
    public function creating(Order_banktransfer $order_banktransfer)
    {
        $order_banktransfer->user_id = auth()->user()->id;
    }

    public function updating(Order_banktransfer $order_banktransfer)
    {
        if ($order_banktransfer->status) {

            //update order_status
            $order_header = Order_header::find($order_banktransfer->order_header_id);
            $order_header->payment_status = true;
            $order_header->save();
        }

        $order_banktransfer->updated_by = auth()->user()->id;
    }
}
