<?php

namespace App\Observers;

use App\Models\Branchrec_order;
use App\Models\Delivery;
use App\Models\Delivery_detail;
use App\Models\Delivery_item;
use App\Models\Order_status;
use App\Models\Waybill;
use App\Models\Waybill_status;

class DeliveryObserver
{

    public function creating(Delivery $delivery)
    {
        if ($delivery->delivery_type == 0) {
            $waybill = Waybill::find($delivery->waybill_id);
            if (isset($waybill)) {
                $waybill->waybill_status = 'delivery';
                $waybill->save();
                Waybill_status::create([
                    'waybill_id' => $delivery->waybill_id,
                    'status' => 'delivery',
                    'user_id' => auth()->user()->id,
                ]);
            }
        }
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
            $delivery_details = Delivery_detail::where('delivery_item_id', $delivery_item->id)->get();
            foreach ($delivery_details as $delivery_detail) {

                $branchrec_order = Branchrec_order::find($delivery_detail->order_header_id);
                $branchrec_order->order_status = 'branch warehouse';
                $branchrec_order->save();
                Order_status::create([
                    'order_header_id' => $delivery_detail->order_header_id,
                    'status' => 'branch warehouse',
                    'user_id' => auth()->user()->id,
                ]);
            }
        }
        if ($delivery->delivery_type ==  0) {
            $waybill = \App\Models\Waybill::find($delivery->waybill_id);
            $waybill->waybill_status = 'completed';
            $waybill->save();
            Waybill_status::create([
                'waybill_id' => $waybill->id,
                'status' => 'completed',
                'user_id' => auth()->user()->id,
            ]);
        }
    }
}
