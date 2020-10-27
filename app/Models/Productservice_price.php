<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Productservice_price extends Model
{

    protected $fillable = [
        'product_id', 'from_branch_id', 'unit_id', 'price', 'district', 'province', 'user_id', 'updated_by'
    ];

    protected $table = 'productservice_price';

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }

    public function from_branch()
    {
        return $this->belongsTo('App\Models\Branch', 'from_branch_id');
    }
}
