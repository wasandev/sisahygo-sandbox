<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tiretype extends Model
{

    protected $fillable = [
        'name', 'user_id', 'updated_by'
    ];

    public function car()
    {
        return $this->belongsTo('App\Modeles\Car');
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
