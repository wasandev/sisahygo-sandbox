<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Serviceprice extends Model
{

    protected $fillable = [
        'name', 'status', 'pricetypes', 'startrate', 'oversizerate', 'start_date', 'end_date',
        'user_id', 'updated_by'
    ];

    protected $casts = [
        'start_date' => 'datetime:d-m-Y',
        'end_date' => 'datetime:d-m-Y',
    ];

    public function serviceprice_items()
    {
        return $this->hasMany('App\Models\Serviceprice_item');
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
