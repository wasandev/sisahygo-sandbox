<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carstyle extends Model
{

    protected $fillable = [
        'name', 'user_id', 'updated_by'
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
    /**
     * Get all of the cars for the Carstyle
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
