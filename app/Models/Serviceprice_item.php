<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Serviceprice_item extends Model
{

    protected $fillable = [
        'name', 'serviceprice_id', 'parcel_id', 'from_branch_id', 'district', 'province', 'price', 'user_id', 'updated_by'
    ];

    public function serviceprice()
    {
        return $this->belongsTo('App\Models\Serviceprice');
    }
    public function from_branch()
    {
        return $this->belongsTo('App\Models\Branch', 'from_branch_id');
    }

    public function parcel()
    {
        return $this->belongsTo('App\Models\Parcel');
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
