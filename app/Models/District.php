<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class District extends Model
{

    protected $table = 'district';

    protected $fillable = [
        'name', 'province_id'
    ];

    public function province()
    {
        return $this->belongsTo('App\Models\Province');
    }

    public function sub_districts()
    {
        return $this->hasMany('App\Models\SubDistrict');
    }

    public function branch_area()
    {
        return $this->hasMany('App\Models\Branch_area', 'district', 'name');
    }
    public function pricezones()
    {
        return $this->belongsToMany('App\Models\Pricezone')->withPivot('express_fee', 'faraway_fee');
    }
}
