<?php

namespace App\Observers;

use App\Models\Vendor;

class VendorObserver
{
    public function creating(Vendor $vendor)
    {
        $vendor->user_id = auth()->user()->id;
    }

    public function updating(Vendor $vendor)
    {
        $vendor->updated_by = auth()->user()->id;
    }
}
