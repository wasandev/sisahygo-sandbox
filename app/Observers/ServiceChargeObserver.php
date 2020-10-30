<?php

namespace App\Observers;

use App\Models\Service_charge;

class ServiceChargeObserver
{
    public function creating(Service_charge $service_charge)
    {
        $service_charge->user_id = auth()->user()->id;
    }

    public function updating(Service_charge $service_charge)
    {
        $service_charge->updated_by = auth()->user()->id;
    }
}
