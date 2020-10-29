<?php

namespace App\Observers;

use \Illuminate\Support\Facades\Auth;
use App\Models\Position;

class PositionObserver
{
    public function creating(Position $position)
    {
        if (Auth::check()) {
            $position->user_id = auth()->user()->id;
        } else {
            $position->user_id = 1;
        }
    }
    //retrieved
    public function updating(Position $position)
    {
        $position->updated_by = auth()->user()->id;
    }
}
