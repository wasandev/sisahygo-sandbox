<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charter_job_insurance extends Model
{

    protected $fillable = [
        'charter_job_id', 'vendor_id', 'insurance_no', 'insurance_fee', 'insurance_cost', 'user_id', 'updated_by'
    ];

    public function charter_job()
    {
        return $this->belongsTo('App\Models\Charter_job');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }
}
