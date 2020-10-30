<?php

namespace App\Observers;

use App\Models\Branch_route_district;

class BranchRouteDistrictObserver
{
    public function creating(Branch_route_district $branch_route_district)
    {
        $branch_route_district->user_id = auth()->user()->id;
    }

    public function updating(Branch_route_district $branch_route_district)
    {
        $branch_route_district->updated_by = auth()->user()->id;
    }
}
