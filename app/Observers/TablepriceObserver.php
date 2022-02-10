<?php

namespace App\Observers;

use App\Models\Tableprice;

class TablepriceObserver
{
    public function creating(Tableprice $tableprice)
    {
        $tableprice->user_id = auth()->user()->id;
    }

    public function updating(Tableprice $tableprice)
    {
        $tableprice->updated_by = auth()->user()->id;
    }
}
