<?php

namespace App\Observers;

use App\Models\Charter_route_cost;

class CharterRouteCostObserver
{
    public function creating(Charter_route_cost $charter_route_cost)
    {
        $charter_route_cost->user_id = auth()->user()->id;
    }

    public function updating(Charter_route_cost $charter_route_cost)
    {
        $charter_route_cost->updated_by = auth()->user()->id;
    }
}
