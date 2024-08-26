<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branchrec_waybill extends Model
{
    use HasFactory;
    protected $table = 'waybills';

    protected $fillable = [
        'waybill_status', 'arrivaled_at','branch_car_income'
    ];

    protected $casts = [
        'arrivaled_at' => 'datetime',
        'waybill_date' => 'date',
        'departure_at' => 'datetime',
        'arrival_at' => 'datetime',
    ];

    public function routeto_branch()
    {
        return $this->belongsTo('App\Models\Routeto_branch');
    }
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

    public function branchrec_orders()
    {
        return $this->hasMany('App\Models\Branchrec_order', 'waybill_id');
    }
    public function waybill_statuses()
    {
        return $this->hasMany('App\Models\Waybill_status', 'waybill_id');
    }
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
    public function to_branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_rec_id');
    }
}
