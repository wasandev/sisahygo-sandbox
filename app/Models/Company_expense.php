<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company_expense extends Model
{

    protected $fillable = [
        'name', 'user_id', 'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
