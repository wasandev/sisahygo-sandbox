<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery_detail extends Model
{
    use HasFactory;
    protected $fillable = [
        'delivery_item_id', 'order_header_id', 'delivery_status', 'payment_status', 'user_id',
        'updated_by'
    ];

    public function delivery_item()
    {
        return $this->belongsTo('App\Models\Delivery_item');
    }


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function branchrec_order()
    {
        return $this->belongsTo('App\Models\Branchrec_order', 'order_header_id');
    }

    public function order_details()
    {
        return $this->hasMany('App\Models\Order_detail', 'order_header_id', 'order_header_id');
    }
}
