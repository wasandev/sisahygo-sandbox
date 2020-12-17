<?php

namespace App\Observers;

use App\Models\Branchrec_order;
use App\Models\Delivery;
use App\Models\Delivery_item;


class DeliveryItemObserver
{

    public function creating(Delivery_item $delivery_item)
    {
        $branchrec_order = Branchrec_order::find($delivery_item->order_header_id);
        $branchrec_order->order_status = 'delivery';
        $branchrec_order->save();

        $delivery = Delivery::find($delivery_item->delivery_id);
        $receipt_amount = $delivery->receipt_amount;
        $delivery->receipt_amount = $receipt_amount + $branchrec_order->order_amount;
        $delivery->save();
    }

    public function updating(Delivery_item $delivery_item)
    {
        // $delivery_items = \App\Models\Delivery_item::where('delivery_id', $delivery->id);

        // if ($delivery->completed) {
        //     for
        // }
    }
    /**
     * Handle the post "deleted" event.
     *
     * @param  \App\Post  $post
     * @return void
     */
    public function deleting(Delivery_item $delivery_item)
    {
        $branchrec_order = Branchrec_order::find($delivery_item->order_header_id);
        $branchrec_order->order_status = 'branch warehouse';
        $branchrec_order->save();

        $delivery = Delivery::find($delivery_item->delivery_id);
        $receipt_amount = $delivery->receipt_amount;
        if ($receipt_amount > 0) {
            $delivery->receipt_amount = $receipt_amount - $branchrec_order->order_amount;
            $delivery->save();
        }
    }
}
