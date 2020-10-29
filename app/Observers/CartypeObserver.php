<?php

namespace App\Observers;

use \Illuminate\Support\Facades\Auth;
use App\Models\Cartype;

class CartypeObserver
{
    public function creating(Cartype $cartype)
    {
        if (Auth::check()) {
            $cartype->user_id = auth()->user()->id;
        } else {
            $cartype->user_id = 1;
        }
    }

    public function updating(Cartype $cartype)
    {
        $cartype->updated_by = auth()->user()->id;
    }
}
