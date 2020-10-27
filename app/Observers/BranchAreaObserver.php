<?php

namespace App\Observers;

use App\Models\Branch_area;

class BranchAreaObserver
{
    public function creating(Branch_area $branch_area)
    {
        $branch_area->user_id = auth()->user()->id;
    }

    public function updating(Branch_area $branch_area)
    {
        $branch_area->updated_by = auth()->user()->id;
    }
}
