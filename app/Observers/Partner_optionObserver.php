<?php

namespace App\Observers;

use App\Models\Partner_option;

class Partner_optionObserver
{
    public function creating(Partner_option $partner_option)
    {
        $partner_option->user_id = auth()->user()->id;
    }

    public function updating(Partner_option $partner_option)
    {
        $partner_option->updated_by = auth()->user()->id;
    }
}
