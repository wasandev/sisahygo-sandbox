<?php

namespace App\Observers;

use App\Models\Incometype;
use App\Models\Withholdingtax;

class WithholdingtaxObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function creating(Withholdingtax $withholdingtax)
    {
        $withholdingtax->user_id = auth()->user()->id;
        $incometype = Incometype::find($withholdingtax->incometype_id);
        $withholdingtax->tax_amount = $withholdingtax->pay_amount * ($incometype->taxrate / 100);
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updating(Withholdingtax $withholdingtax)
    {
        $withholdingtax->updated_by = auth()->user()->id;
        $incometype = Incometype::find($withholdingtax->incometype_id);
        $withholdingtax->tax_amount = $withholdingtax->pay_amount * ($incometype->taxrate / 100);
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(Withholdingtax $withholdingtax)
    {
        //
    }
}
