<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service_charge extends Model
{

    protected $fillable = [
        'name', 'status', 'amount', 'user_id', 'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    /**
     * The charter_jobs that belong to the Service_charge
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function charter_jobs()
    {
        return $this->belongsToMany(Charter_job::class, 'service_charge_charter_job', 'charter_job_id', 'service_charge_id')
            ->withPivot('amount');
    }

    public function order_headers()
    {
        return $this->belongsToMany(Order_header::class, 'service_charges_order_header', 'order_header_id', 'service_charge_id')
            ->withPivot('servive_amount', 'description');
    }
}
