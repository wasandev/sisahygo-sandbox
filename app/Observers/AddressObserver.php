<?php

namespace App\Observers;

use App\Models\Address;

class AddressObserver
{
    public function creating(Address $address)
    {
        $address->user_id = auth()->user()->id;
    }

    public function updating(Address $address)
    {
        $address->updated_by = auth()->user()->id;
    }
}
