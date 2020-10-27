<?php

namespace App\Observers;

use App\Models\Vendor;

class VendorObserver
{
    public function creating(Vendor $vendor)
    {
        $vendor->user_id = auth()->user()->id;
    }

    public function saving(Vendor $vendor)
    {
        $vendor->user_id = auth()->user()->id;
    }
}
