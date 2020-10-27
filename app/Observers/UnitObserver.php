<?php

namespace App\Observers;

use App\Models\Unit;

class UnitObserver
{
    public function creating(Unit $unit)
    {
        $unit->user_id = auth()->user()->id;
    }

    public function saving(Unit $unit)
    {
        $unit->user_id = auth()->user()->id;
    }
}
