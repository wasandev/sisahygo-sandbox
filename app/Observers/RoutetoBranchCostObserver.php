<?php

namespace App\Observers;

use App\Models\Routeto_branch_cost;

class RoutetoBranchCostObserver
{
    public function creating(Routeto_branch_cost $routeto_branch_cost)
    {
        $routeto_branch_cost->user_id = auth()->user()->id;
    }

    public function updating(Routeto_branch_cost $routeto_branch_cost)
    {
        $routeto_branch_cost->updated_by = auth()->user()->id;
    }
}
