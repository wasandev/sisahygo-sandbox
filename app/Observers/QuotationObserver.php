<?php

namespace App\Observers;

use App\Models\Quotation;
use Illuminate\Support\Carbon;

class QuotationObserver
{
    public function creating(Quotation $quotation)
    {
        $quotation->user_id = auth()->user()->id;
        $quotation->quotation_no = Quotation::nextQuotationNumber();
        $quotation->quotation_date = Carbon::now()->toDateTimeString();
    }

    public function updating(Quotation $quotation)
    {
        $quotation->updated_by = auth()->user()->id;
        $quotation->status = 'edit';
    }
}
