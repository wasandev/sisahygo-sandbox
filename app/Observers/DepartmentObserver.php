<?php

namespace App\Observers;

use App\Models\Department;

class DepartmentObserver
{
    public function creating(Department $department)
    {
        $department->user_id = auth()->user()->id;
    }

    public function updating(Department $department)
    {
        $department->updated_by = auth()->user()->id;
    }
}
