<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tableprice extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'status',  'start_date', 'end_date',
        'user_id', 'updated_by'
    ];

    protected $casts = [
        'start_date' => 'datetime:d-m-Y',
        'end_date' => 'datetime:d-m-Y',
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function productservice_prices()
    {
        return $this->hasMany('App\Models\Productservice_price');
    }
}
