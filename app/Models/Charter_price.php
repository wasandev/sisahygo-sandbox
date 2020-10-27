<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charter_price extends Model
{

    protected $fillable = [
        'cartype_id', 'carstyle_id', 'charter_route_id',  'status', 'price', 'pickuppoint', 'overpointcharge', 'user_id', 'updated_by'
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
}
