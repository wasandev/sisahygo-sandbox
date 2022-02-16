<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waybill_charter extends Model
{
    use HasFactory;
    protected $table = 'waybills';
    protected $fillable = [
        'waybill_no', 'waybill_date', 'waybill_status', 'waybill_type',
        'charter_route_id', 'car_id', 'driver_id', 'branchcar_id',
        'waybill_amount', 'waybill_payble', 'waybill_date', 'waybill_income', 'branch_car_rate', 'branch_car_income',
        'loader_id', 'departure_at', 'arrival_at', 'arrivaled_at', 'user_id', 'updated_by', 'branch_id', 'branch_rec_id'
    ];
    protected $casts = [
        'waybill_date' => 'date',
        'departure_at' => 'datetime',
        'arrival_at' => 'datetime',
        'arrivaled_at' => 'datetime'
    ];

    public function charter_route()
    {
        return $this->belongsTo('App\Models\Charter_route');
    }

    public function car()
    {
        return $this->belongsTo('App\Models\Car');
    }

    public function driver()
    {
        return $this->belongsTo('App\Models\Employee', 'driver_id');
    }



    public function loader()
    {
        return $this->belongsTo('App\Models\User', 'loader_id',);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }


    public function order_charters()
    {
        return $this->hasMany('App\Models\Order_charter', 'waybill_id');
    }
    public function waybill_statuses()
    {
        return $this->hasMany('App\Models\Waybill_status', 'waybill_id');
    }
}
