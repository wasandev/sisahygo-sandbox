<?php

namespace App\Observers;

use App\Models\Branchrec_order;
use App\Models\Delivery;
use App\Models\Delivery_item;

class DeliveryObserver
{

    public function creating(Delivery $delivery)
    {
    }

    public function updating(Delivery $delivery)
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
    public function deleting(Delivery $delivery)
    {
        $delivery_items = Delivery_item::where('delivery_id', $delivery->id)->get();
        foreach ($delivery_items as $delivery_item) {
            $branchrec_order = Branchrec_order::find($delivery_item->order_header_id);
            $branchrec_order->order_status = 'branch warehouse';
            $branchrec_order->save();
        }
    }
}
