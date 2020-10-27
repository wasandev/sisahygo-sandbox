<?php

namespace App\Observers;

use App\Models\Branch;

class BranchObserver
{
    public function creating(Branch $branch)
    {
        $branch->user_id = auth()->user()->id;
    }

    public function updating(Branch $branch)
    {
        $branch->updated_by = auth()->user()->id;
    }
}
