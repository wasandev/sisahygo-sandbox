<?php

namespace App\Observers;

use App\Models\Department;
use \Illuminate\Support\Facades\Auth;

class DepartmentObserver
{
    public function creating(Department $department)
    {
        if (Auth::check()) {
            $department->user_id = auth()->user()->id;
        } else {
            $department->user_id = 1;
        }
    }

    public function updating(Department $department)
    {
        $department->updated_by = auth()->user()->id;
    }
}
