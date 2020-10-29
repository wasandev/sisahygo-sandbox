<?php

namespace App\Observers;

use App\Models\Serviceprice_item;

class Serviceprice_itemObserver
{
    public function creating(Serviceprice_item $serviceprice_item)
    {
        $serviceprice_item->user_id = auth()->user()->id;
    }

    public function updating(Serviceprice_item $serviceprice_item)
    {
        $serviceprice_item->updated_by = auth()->user()->id;
    }
}
