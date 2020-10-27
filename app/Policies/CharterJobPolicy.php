<?php

namespace App\Policies;

use App\Models\Charter_job;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CharterJobPolicy
{
    use HandlesAuthorization;


    public function view(User $user, Charter_job $charter_job)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view charter_jobs');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create charter_jobs');
    }


    public function update(User $user, Charter_job $charter_job)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('edit charter_jobs') && $charter_job->status == 'New';
    }


    public function delete(User $user, Charter_job $charter_job)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete charter_jobs');
    }
}
