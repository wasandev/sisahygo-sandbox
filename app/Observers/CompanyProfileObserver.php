<?php

namespace App\Observers;

use App\Models\CompanyProfile;

class CompanyProfileObserver
{
    public function creating(CompanyProfile $companyprofile)
    {
        $companyprofile->user_id = auth()->user()->id;
    }

    public function updating(CompanyProfile $companyprofile)
    {
        $companyprofile->updated_by = auth()->user()->id;
    }
}
