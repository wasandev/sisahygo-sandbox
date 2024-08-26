<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $fillable = [
        'delivery_no', 'delivery_date', 'delivery_type', 'waybill_id',
        'branch_id', 'branch_route_id', 'car_id', 'driver_id', 'sender_id', 'description', 'receipt_amount', 'user_id',
        'updated_by', 'completed','mile_start_number','mile_end_number','delivery_mile'
    ];

    protected $casts = [
        'delivery_date' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }

    public function branch_route()
    {
        return $this->belongsTo('App\Models\Branch_route');
    }

    public function car()

    {
        return $this->belongsTo('App\Models\Car');
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
        return $this->belongsTo('App\Models\Employee', 'driver_id', 'id');
    }
    public function sender()
    {
        return $this->belongsTo('App\Models\User', 'sender_id', 'id');
    }
    public function delivery_items()
    {
        return $this->hasMany('App\Models\Delivery_item');
    }
    public function delivery_costitems()
    {
        return $this->hasMany('App\Models\Delivery_costitem');
    }
}
