<?php

namespace App\Observers;

use App\Models\Quotation;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class QuotationObserver
{
    public function creating(Quotation $quotation)
    {
        $quotation_no = IdGenerator::generate(['table' => 'quotations', 'field' => 'quotation_no', 'length' => 15, 'prefix' => 'Q' . date('Ymd')]);

        $quotation->user_id = auth()->user()->id;
        $quotation->quotation_no = $quotation_no;
        $quotation->quotation_date = today();
    }

    public function updating(Quotation $quotation)
    {
        $quotation->updated_by = auth()->user()->id;
        $quotation->status = 'edit';
    }
}
