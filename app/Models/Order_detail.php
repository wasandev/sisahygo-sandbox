<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_header_id', 'product_id', 'unit_id', 'price', 'amount', 'remark', 'user_id', 'updated_by'
    ];
    public function order_header()
    {
        return $this->belongsTo('App\Models\Order_header');
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Customer');
    }
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\user');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
}
