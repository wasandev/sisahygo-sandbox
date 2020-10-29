<?php

namespace App\Observers;

use App\Models\Charter_route;

class CharterRouteObserver
{
    public function creating(Charter_route $charter_route)
    {
        $charter_route->user_id = auth()->user()->id;
    }

    public function updating(Charter_route $charter_route)
    {
        $charter_route->updated_by = auth()->user()->id;
    }
}
