<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driving_license_type extends Model
{
    protected $fillable = [
        'name', 'user_id', 'updated_by'
    ];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
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
