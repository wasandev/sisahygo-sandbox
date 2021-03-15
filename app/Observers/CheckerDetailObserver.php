<?php

namespace App\Observers;

use App\Models\Checker_detail;
use App\Models\Productservice_price;

class CheckerDetailObserver
{
    public function creating(Checker_detail $checker_detail)
    {
        $product_price =  Productservice_price::find($checker_detail->productservice_price_id);


        if ($product_price) {
            $checker_detail->product_id = $product_price->product_id;
            $checker_detail->unit_id = $product_price->unit_id;
            $checker_detail->price = $product_price->price;
        }
        if ($checker_detail->unit->name == 'กิโลกรัม') {
            $checker_detail->weight = 1;
        }
        $checker_detail->user_id = auth()->user()->id;
    }
    public function updating(Checker_detail $checker_detail)
    {
        $product_price =  Productservice_price::find($checker_detail->productservice_price_id);


        if ($product_price) {
            $checker_detail->product_id = $product_price->product_id;
            $checker_detail->unit_id = $product_price->unit_id;
            $checker_detail->price = $product_price->price;
        }
        $checker_detail->updated_by = auth()->user()->id;
        if ($checker_detail->unit->name == 'กิโลกรัม') {
            $checker_detail->weight = 1;
        }
    }
}
