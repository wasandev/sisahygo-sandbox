<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    public function creating(Product $product)
    {
        $product->user_id = auth()->user()->id;
        $product->status = '1';
    }

    public function updating(Product $product)
    {
        $product->updated_by = auth()->user()->id;
        $product->status = '1';
    }
}
