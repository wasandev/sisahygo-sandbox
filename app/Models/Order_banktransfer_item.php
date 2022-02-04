<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_banktransfer_item extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_banktransfer_id', 'order_header_id', 'user_id', 'updated_by'
    ];
    public function order_header()
    {
        return $this->belongsTo('App\Models\Order_header');
    }
    public function order_banktransfer()
    {
        return $this->belongsTo('App\Models\Order_banktransfer');
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
