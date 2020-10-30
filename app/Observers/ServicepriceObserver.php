<?php

namespace App\Observers;

use App\Models\Serviceprice;

class ServicepriceObserver
{
    public function creating(Serviceprice $serviceprice)
    {
        $serviceprice->user_id = auth()->user()->id;
    }

    public function updating(Serviceprice $serviceprice)
    {
        $serviceprice->updated_by = auth()->user()->id;
    }
}
