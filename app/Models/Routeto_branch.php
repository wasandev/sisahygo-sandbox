<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;


class Routeto_branch extends Pivot
{
    public $incrementing = true;
    protected $table = 'routeto_branch';

    protected $fillable = [
        'branch_id',
        'dest_branch_id',
        'name',
        'distance',
        'duration',
        'collectdays',
        'user_id', 'updated_by'
    ];



    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
    public function dest_branch()
    {
        return $this->belongsTo('App\Models\Branch', 'dest_branch_id');
    }

    public function routeto_branch_costs()
    {
        return $this->hasMany('App\Models\Routeto_branch_cost', 'routeto_branch_id');
    }
}
