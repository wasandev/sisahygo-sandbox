<?php

namespace App\Observers;

use \Illuminate\Support\Facades\Auth;
use App\Models\Product_style;

class Product_styleObserver
{
    public function creating(Product_style $product_style)
    {
        if (Auth::check()) {
            $product_style->user_id = auth()->user()->id;
        } else {
            $product_style->user_id = 1;
        }
    }

    public function updating(Product_style $product_style)
    {
        $product_style->updated_by = auth()->user()->id;
    }
}
