<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Product_style extends Model
{

    protected $fillable = [
        'name', 'user_id', 'updated_by'
    ];


    public function products()
    {
        return $this->hasMany('App\Models\Product');
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
