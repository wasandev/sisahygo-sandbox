<?php

namespace App\Observers;

use App\Models\Productservice_newprice;
use App\Models\Productservice_price;

class ProductService_newpriceObserver
{
    public function creating(Productservice_newprice $productservice_newprice)
    {
        $productservice_newprice->user_id = auth()->user()->id;
    }

    public function updating(Productservice_newprice $productservice_newprice)


    {
        $productservice_newprice->updated_by = auth()->user()->id;
    }
}
