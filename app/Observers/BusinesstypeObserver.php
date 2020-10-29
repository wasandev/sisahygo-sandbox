<?php

namespace App\Observers;

use App\Models\Businesstype;
use \Illuminate\Support\Facades\Auth;

class BusinesstypeObserver
{
    public function creating(Businesstype $businesstype)
    {
        if (Auth::check()) {
            $businesstype->user_id = auth()->user()->id;
        } else {
            $businesstype->user_id = 1;
        }
    }

    public function updating(Businesstype $businesstype)
    {
        $businesstype->updated_by = auth()->user()->id;
    }
}
