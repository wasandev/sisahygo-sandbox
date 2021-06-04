<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_status extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_header_id', 'status', 'user_id', 'updated_by'
    ];
    protected $casts = [
        
        'created_at' => 'datetime',
    ];
    public function order_header()
    {
        return $this->belongsTo('App\Models\Order_header');
    }
    public function order_checker()
    {
        return $this->belongsTo('App\Models\Order_checker', 'order_header_id');
    }
    public function order_loader()
    {
        return $this->belongsTo('App\Models\Order_loader', 'order_header_id');
    }

    public function order_cash()
    {
        return $this->belongsTo('App\Models\Order_cash', 'order_header_id');
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
