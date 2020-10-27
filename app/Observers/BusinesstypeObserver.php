<?php

namespace App\Observers;

use App\Models\Businesstype;

class BusinesstypeObserver
{
    public function creating(Businesstype $businesstype)
    {
        $businesstype->user_id = auth()->user()->id;
    }

    public function updating(Businesstype $businesstype)
    {
        $businesstype->updated_by = auth()->user()->id;
    }
}
