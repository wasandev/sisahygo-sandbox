<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch_route_district extends Model
{

    protected $fillable = [
        'branch_route_id', 'branch_area_id',
        'distance', 'user_id', 'updated_by'
    ];

    public function branch_route()
    {
        return $this->belongsTo('App\Models\Branch_route');
    }

    public function branch_area()
    {
        return $this->belongsTo('App\Models\Branch_area');
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
