<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{

    protected $fillable = [
        'name', 'width', 'length', 'height', 'weight', 'user_id', 'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
    public function serviceprice_items()
    {
        return $this->hasMany('App\Models\Serviceprice_item');
    }
}
