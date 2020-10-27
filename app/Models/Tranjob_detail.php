<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Tranjob_detail extends Model
{

    protected $fillable = [
        'tranjob_id',
        'product_id',
        'amount',
        'unit_id',
        'tranprice',
        'productprice',
        'remark', 'user_id',
        'updated_by'
    ];

    public function tranjob()
    {
        return $this->belongsTo('App\Models\Tranjob');
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
