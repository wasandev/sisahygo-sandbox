<?php

namespace App\Observers;

use App\Models\Customer_product;
use App\Models\Customer_product_price;

class CustomerShippingCostObserver
{
    public function creating(Customer_product_price $customer_product_price)
    {
        $customer_product_price->user_id = auth()->user()->id;
    }

    public function updating(Customer_product_price $customer_product_price)
    {
        $customer_product_price->updated_by = auth()->user()->id;
    }
}
