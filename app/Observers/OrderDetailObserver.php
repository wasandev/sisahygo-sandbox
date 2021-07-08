<?php

namespace App\Observers;

use App\Models\Order_detail;
use App\Models\Product;
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
        } elseif ($order_detail->weight == 0) {
            $product = Product::where('id', $order_detail->product_id)
                ->where('unit_id', $order_detail->unit_id)->first();
            if (isset($product->weight)) {
                $order_detail->weight = $product->weight;
            } else {
                $order_detail->weight = 0;
            }
        }
        $order_detail->user_id = auth()->user()->id;
        //update product weight
        if ($order_detail->unit->name <> 'กิโลกรัม') {
            Product::where('id', $order_detail->product_id)
                ->where('unit_id', $order_detail->unit_id)
                ->where('weight', 0)
                ->update(['weight' => $order_detail->weight]);
        }
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
        } elseif ($order_detail->weight == 0) {
            $product = Product::where('id', $order_detail->product_id)
                ->where('unit_id', $order_detail->unit_id)->first();

            $order_detail->weight = $product->weight;
        }
        if ($order_detail->unit->name <> 'กิโลกรัม') {
            Product::where('id', $order_detail->product_id)
                ->where('unit_id', $order_detail->unit_id)
                ->where('weight', 0)
                ->update(['weight' => $order_detail->weight]);
        }
    }
}
