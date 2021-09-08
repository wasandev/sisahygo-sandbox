<?php

namespace App\Observers;

use App\Models\Incometype;

class IncometypeObserver
{
    public function creating(Incometype $incometype)
    {
        $incometype->user_id = auth()->user()->id;
    }
    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updating(Incometype $incometype)
    {
        $incometype->updated_by = auth()->user()->id;
    }
}
