<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{

    protected $fillable = [
        'name', 'user_id', 'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
