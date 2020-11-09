<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation_item extends Model
{

    protected $fillable = [
        'quotation_id', 'from_address_id', 'to_address_id', 'cartype_id', 'carstyle_id', 'product_id', 'number', 'unit_id', 'total_weight', 'amount', 'pickup_date', 'delivery_date',
        'user_id', 'updated_by'
    ];
    protected $casts = [
        'pickup_date' => 'datetime',
        'delivery_date' => 'datetime'
    ];
    public function quotation()
    {
        return $this->belongsTo('App\Models\Quotation');
    }
    public function from_address()
    {
        return $this->belongsTo('App\Models\Address');
    }
    public function to_address()
    {
        return $this->belongsTo('App\Models\Address');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
    public function cartype()
    {
        return $this->belongsTo('App\Models\Cartype');
    }
    public function carstyle()
    {
        return $this->belongsTo('App\Models\Carstyle');
    }
}
