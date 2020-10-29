<?php

namespace App\Observers;

use \Illuminate\Support\Facades\Auth;
use App\Models\Unit;

class UnitObserver
{
    public function creating(Unit $unit)
    {
        if (Auth::check()) {
            $unit->user_id = auth()->user()->id;
        } else {
            $unit->user_id = 1;
        }
    }

    public function updating(Unit $unit)
    {
        $unit->updated_by = auth()->user()->id;
    }
}
