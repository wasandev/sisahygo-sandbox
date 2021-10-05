<?php

namespace App\Observers;

use App\Models\Billingnote_file;

class BillingnoteFileObserver
{
    public function creating(Billingnote_file $billingnote_file)
    {
        $billingnote_file->user_id = auth()->user()->id;
    }

    public function updating(Billingnote_file $billingnote_file)
    {
        $billingnote_file->updated_by = auth()->user()->id;
    }
}
