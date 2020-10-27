<?php

namespace App\Observers;

use App\Models\Tiretype;


class TiretypeObserver
{
    public function creating(Tiretype $tiretype)
    {
        $tiretype->user_id = auth()->user()->id;
    }

    public function updating(Tiretype $tiretype)
    {
        $tiretype->updated_by = auth()->user()->id;
    }
}
