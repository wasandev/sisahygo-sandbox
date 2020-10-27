<?php

namespace App\Observers;

use App\Models\Position;

class PositionObserver
{
    public function creating(Position $position)
    {
        $position->user_id = auth()->user()->id;
    }
    //retrieved
    public function updating(Position $position)
    {
        $position->updated_by = auth()->user()->id;
    }
}
