<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Branch_area extends Model
{

    protected $fillable = [
        'branch_id',
        'district',
        'province',
        'location_lat',
        'location_lng',
        'deliverydays',
        'user_id',
        'updated_by'
    ];

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }


    public function charter_routes()
    {
        return $this->hasMany('App\Models\Charter_route');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
}
