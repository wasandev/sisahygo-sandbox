<?php

namespace App\Observers;

use App\Models\Branch_route;

class BranchRouteObserver
{
    public function creating(Branch_route $branch_route)
    {
        $branch_route->user_id = auth()->user()->id;
    }

    public function updating(Branch_route $branch_route)
    {
        $branch_route->updated_by = auth()->user()->id;
    }
}
