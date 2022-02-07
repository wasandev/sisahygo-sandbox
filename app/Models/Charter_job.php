<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charter_job extends Model
{

    protected $fillable = [
        'status', 'job_no', 'job_date', 'branch_id', 'customer_id', 'reference',
        'quotation_id', 'paymenttype', 'paymentpoint', 'charter_price_id', 'terms',
        'sub_total', 'discount', 'tax_amount',
        'total', 'employee_id', 'user_id', 'updated_by', 'order_header_id', 'car_id', 'driver_id',
        'waybill_payable', 'waybill_amount'

    ];

    protected $casts = [
        'job_date' => 'datetime',
        'created_at' => 'datetime',
        'updated-_at' => 'datetime'
    ];
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }
    public function quotation()
    {
        return $this->belongsTo('App\Models\Quotation');
    }

    public function charter_price()
    {
        return $this->belongsTo('App\Models\Charter_price');
    }

    public function charter_job_items()
    {
        return $this->hasMany('App\Models\Charter_job_item');
    }

    public function charter_job_statuses()
    {
        return $this->hasMany('App\Models\Charter_job_status');
    }
    public function charter_job_insurance()
    {
        return $this->hasOne('App\Models\Charter_job_insurance');
    }

    public function service_charges()
    {
        return $this->belongsToMany('App\Models\Service_charge', 'service_charge_charter_job', 'charter_job_id', 'service_charge_id')
            ->withPivot('amount');
    }

    /**
     * Get the order_header that owns the Charter_job
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order_charter()
    {
        return $this->belongsTo(Order_charter::class, 'order_header_id');
    }
}
