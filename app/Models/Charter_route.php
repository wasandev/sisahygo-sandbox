<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charter_route extends Model
{

    protected $fillable = [
        'name',  'branch_area_id', 'to_district', 'to_province', 'status', 'distance', 'user_id', 'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function charter_prices()
    {
        return $this->hasMany('App\Models\Charter_price');
    }

    public function charter_route_costs()
    {
        return $this->hasMany('App\Models\Charter_route_cost');
    }
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
    public function branch_area()
    {
        return $this->belongsTo('App\Models\Branch_area');
    }
}
