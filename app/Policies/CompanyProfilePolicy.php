<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CompanyProfile;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyProfilePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view companyprofile');
    }

    /**
     * Determine whether the user can view the company profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CompanyProfile  $companyProfile
     * @return mixed
     */
    public function view(User $user, CompanyProfile $companyProfile)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view companyprofile');
    }

    /**
     * Determine whether the user can create company profiles.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create companyprofile');
    }

    /**
     * Determine whether the user can update the company profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CompanyProfile  $companyProfile
     * @return mixed
     */
    public function update(User $user, CompanyProfile $companyProfile)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('edit companyprofile');
    }

    /**
     * Determine whether the user can delete the company profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CompanyProfile  $companyProfile
     * @return mixed
     */
    public function delete(User $user, CompanyProfile $companyProfile)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete companyprofile');
    }
}
