<?php

namespace App\Observers;

use App\Models\Branch_route_cost;

class BranchRouteCostObserver
{
    public function creating(Branch_route_cost $branch_route_cost)
    {
        $branch_route_cost->user_id = auth()->user()->id;
    }

    public function updating(Branch_route_cost $branch_route_cost)
    {
        $branch_route_cost->updated_by = auth()->user()->id;
    }
}
