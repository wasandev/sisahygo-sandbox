<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer_product_price extends Model
{
    //protected $table = 'customer_product_price';

    protected $fillable = [
        'customer_id', 'product_id', 'from_branch_id', 'district', 'province', 'unit_id', 'price', 'user_id', 'active', 'updated_by'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\customer');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function from_branch()
    {
        return $this->belongsTo('App\Models\Branch', 'from_branch_id');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
}
