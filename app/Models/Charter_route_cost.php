<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charter_route_cost extends Model
{

    protected $fillable = [
        'cartype_id', 'carstyle_id', 'charter_route_id',  'status', 'fuel_cost', 'fuel_amount', 'timespent',  'car_charge', 'driver_charge', 'user_id', 'updated_by'
    ];

    public function charter_route()
    {
        return $this->belongsTo('App\Models\Charter_route');
    }

    public function cartype()
    {
        return $this->belongsTo('App\Models\Cartype');
    }

    public function carstyle()
    {
        return $this->belongsTo('App\Models\Carstyle');
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
