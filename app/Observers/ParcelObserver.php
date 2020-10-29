<?php

namespace App\Observers;

use App\Models\Parcel;

class ParcelObserver
{

    /**
     * Handle the retailer "creating" event.
     *
     * @param  \App\Models\parcel  $parcel
     * @return void
     */
    public function creating(Parcel $parcel)
    {
        $parcel->user_id = auth()->user()->id;
    }
    public function updating(Parcel $parcel)
    {
        $parcel->updated_by = auth()->user()->id;
    }
}
