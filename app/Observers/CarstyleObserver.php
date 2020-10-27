<?php

namespace App\Observers;

use App\Models\Carstyle;

class CarstyleObserver
{
    public function creating(Carstyle $carstyle)
    {
        $carstyle->user_id = auth()->user()->id;
    }

    public function updating(Carstyle $carstyle)
    {
        $carstyle->updated_by = auth()->user()->id;
    }
}
