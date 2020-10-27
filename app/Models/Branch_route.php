<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch_route extends Model
{

    protected $fillable = [
        'branch_id', 'name', 'distance', 'user_id', 'updated_by'
    ];

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }

    public function branch_route_districts()
    {
        return $this->hasMany('App\Models\Branch_route_district');
    }

    public function branch_route_costs()
    {
        return $this->hasMany('App\Models\Branch_route_cost');
    }
}
