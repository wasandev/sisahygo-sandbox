<?php

namespace App\Observers;

use App\Models\Productservice_price;

class ProductServicePriceObserver
{
    public function creating(Productservice_price $productservice_price)
    {
        $productservice_price->user_id = auth()->user()->id;
    }

    public function saving(Productservice_price $productservice_price)
    {
        $productservice_price->user_id = auth()->user()->id;
    }
}
