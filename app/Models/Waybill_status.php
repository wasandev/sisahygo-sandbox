<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waybill_status extends Model
{
    use HasFactory;
    protected $fillable = [
        'waybill_id', 'status', 'user_id', 'updated_by'
    ];

    public function waybill()
    {
        return $this->belongsTo('App\Models\Waybill');
    }
    public function  branchrec_waybill()
    {
        return $this->belongsTo('App\Models\Branchrec_waybill', 'waybill_id');
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
