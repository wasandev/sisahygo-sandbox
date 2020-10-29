<?php

namespace App\Observers;

use \Illuminate\Support\Facades\Auth;
use App\Models\Carstyle;

class CarstyleObserver
{
    public function creating(Carstyle $carstyle)
    {
        if (Auth::check()) {
            $carstyle->user_id = auth()->user()->id;
        } else {
            $carstyle->user_id = 1;
        }
    }

    public function updating(Carstyle $carstyle)
    {
        $carstyle->updated_by = auth()->user()->id;
    }
}
