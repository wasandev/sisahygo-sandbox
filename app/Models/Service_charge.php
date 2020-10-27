<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service_charge extends Model
{

    protected $fillable = [
        'name', 'status', 'amount', 'user_id', 'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
