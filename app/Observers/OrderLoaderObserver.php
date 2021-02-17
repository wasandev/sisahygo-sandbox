<?php

namespace App\Observers;

use App\Models\Order_loader;
use App\Models\Order_status;


class OrderLoaderObserver
{


    public function updating(Order_loader $order_loader)
    {
        if (is_null($order_loader->waybill_id)) {
            $order_status = 'confirmed';
        } else {
            $order_status = 'loaded';
        }
        if (is_null($order_loader->loader_id)) {
            $order_loader->loader_id =  auth()->user()->id;
        }
        $order_loader->order_status = $order_status;

        Order_status::Create([
            'order_header_id' => $order_loader->id,
            'status' => $order_status,
            'user_id' => auth()->user()->id,
        ]);
    }
}
