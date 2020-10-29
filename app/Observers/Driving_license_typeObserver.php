<?php

namespace App\Observers;

use \Illuminate\Support\Facades\Auth;
use App\Models\Driving_license_type;

class Driving_license_typeObserver
{
    public function creating(Driving_license_type $driving_license_type)
    {

        if (Auth::check()) {
            $driving_license_type->user_id = auth()->user()->id;
        } else {
            $driving_license_type->user_id = 1;
        }
    }

    public function updating(Driving_license_type $driving_license_type)
    {
        $driving_license_type->updated_by = auth()->user()->id;
    }
}
