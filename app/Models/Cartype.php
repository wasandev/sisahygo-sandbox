<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cartype extends Model
{

    protected $fillable = [
        'name', 'user_id', 'payload', 'updated_by'
    ];

    public function car()
    {
        return $this->belongsTo('App\Models\Car');
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
