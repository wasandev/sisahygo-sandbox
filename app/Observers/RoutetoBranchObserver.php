<?php

namespace App\Observers;

use App\Models\Routeto_branch;

class RoutetoBranchObserver
{
    public function creating(Routeto_branch $routeto_branch)
    {
        $routeto_branch->user_id = auth()->user()->id;
    }

    public function updating(Routeto_branch $routeto_branch)
    {
        $routeto_branch->updated_by = auth()->user()->id;
    }
}
