<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Charter_job_item extends Model
{

    protected $fillable = [
        'charter_job_id', 'from_address_id', 'to_address_id', 'product_id', 'amount', 'unit_id',
        'total_weight', 'productvalue', 'pickup_date', 'delivery_date', 'user_id', 'updated_by'
    ];

    protected $casts = [
        'pickup_date' => 'datetime',
        'delivery_date' => 'datetime'
    ];

    public function charter_job()
    {
        return $this->belongsTo('App\Models\Charter_job');
    }

    public function from_address()
    {
        return $this->belongsTo('App\Models\Address', 'from_address_id');
    }
    public function to_address()
    {
        return $this->belongsTo('App\Models\Address', 'to_address_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }
}
