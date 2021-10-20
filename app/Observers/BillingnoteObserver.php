<?php

namespace App\Observers;

use App\Models\Billingnote;

class BillingnoteObserver
{
    public function creating(Billingnote $billingnote)
    {
        $billingnote->user_id = auth()->user()->id;
    }

    public function updating(Billingnote $billingnote)
    {
        $billingnote->updated_by = auth()->user()->id;
    }

    public function updated(Billingnote $billingnote)
    {
    }
}
