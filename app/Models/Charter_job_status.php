<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charter_job_status extends Model
{

    protected $fillable = [
        'charter_job_id', 'status', 'user_id', 'updated_by'
    ];
    protected $casts = [

        'created_at' => 'datetime',
        'updated-_at' => 'datetime'
    ];
    public function charter_job()
    {
        return $this->belongsTo('App\Models\Charter_job');
    }
}
