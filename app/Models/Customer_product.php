<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer_product extends Model
{
    protected $table = 'customer_product';

    protected $fillable = [
        'customer_id', 'product_id', 'user_id', 'updated_by'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\customer');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function customer_product_prices()
    {
        return $this->hasMany('App\Models\Customer_product_price');
    }
}
