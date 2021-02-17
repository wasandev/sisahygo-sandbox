<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Routeto_branch_cost extends Model
{

    protected $fillable = [
        'routeto_branch_id', 'cartype_id', 'carstyle_id', 'status',
        'fulltruckrate',  'fuel_cost', 'fuel_amount',  'car_charge',
        'driver_charge', 'user_id', 'updated_by', 'chargeflag', 'chargerate'
    ];

    public function routeto_branch()
    {
        return $this->belongsTo('App\Models\Routeto_branch');
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
