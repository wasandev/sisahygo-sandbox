<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery_item extends Model
{
    use HasFactory;
    protected $fillable = [
        'delivery_id', 'order_header_id', 'delivery_status', 'payment_status', 'user_id',
        'updated_by'
    ];

    public function delivery()
    {
        return $this->belongsTo('App\Models\Delivery');
    }

    public function branchrec_order()
    {
        return $this->belongsTo('App\Models\Branchrec_order', 'order_header_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function driver()
    {
        return $this->belongsTo('App\Models\Employee', 'driver_id');
    }
}
