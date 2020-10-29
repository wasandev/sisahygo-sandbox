<?php

namespace App\Observers;

use \Illuminate\Support\Facades\Auth;
use App\Models\Tiretype;


class TiretypeObserver
{
    public function creating(Tiretype $tiretype)
    {
        if (Auth::check()) {
            $tiretype->user_id = auth()->user()->id;
        } else {
            $tiretype->user_id = 1;
        }
    }

    public function updating(Tiretype $tiretype)
    {
        $tiretype->updated_by = auth()->user()->id;
    }
}
