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
            //check if 'E'
            if ($order_header->paymenttype === 'E') {
                //update delivery item
                $delivery_item = \App\Models\Delivery_item::where('order_header_id', $order_banktransfer->order_header_id)->first();
                $delivery_item->payment_status = true;
                $delivery_item->save();
                //update branch_balance
                $branch_balance = \App\Models\Branch_balance::where('order_header_id', $order_banktransfer->order_header_id)->first();
                $branch_balance->payment_status = true;
                $branch_balance->save();
            }
        }

        $order_banktransfer->updated_by = auth()->user()->id;
    }
}
