<?php

namespace App\Observers;

use App\Models\Order_detail;
use App\Models\Productservice_price;

class OrderDetailObserver
{
    public function creating(Order_detail $order_detail)
    {
        $product_price =  Productservice_price::find($order_detail->productservice_price_id);


        if ($product_price) {
            $order_detail->product_id = $product_price->product_id;
            $order_detail->unit_id = $product_price->unit_id;
            $order_detail->price = $product_price->price;
        }
        if ($order_detail->unit->name == 'กิโลกรัม') {
            $order_detail->weight = 1;
        }
        $order_detail->user_id = auth()->user()->id;
    }
    public function updating(Order_detail $order_detail)
    {
        $product_price =  Productservice_price::find($order_detail->productservice_price_id);


        if ($product_price) {
            $order_detail->product_id = $product_price->product_id;
            $order_detail->unit_id = $product_price->unit_id;
            $order_detail->price = $product_price->price;
        }
        $order_detail->updated_by = auth()->user()->id;
        if ($order_detail->unit->name == 'กิโลกรัม') {
            $order_detail->weight = 1;
        }
    }
}
