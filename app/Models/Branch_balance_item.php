<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch_balance_item extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_balance_id', 'order_header_id', 'payment_status', 'user_id', 'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function branch_balance()
    {
        return $this->belongsTo('App\Models\Branch_balance', 'order_header_id');
    }
    public function branch_balance_partner()
    {
        return $this->belongsTo('App\Models\Branch_balance_partner', 'order_header_id');
    }
    public function branchrec_order()
    {
        return $this->belongsTo('App\Models\Branchrec_order', 'order_header_id');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
    public function order_details()
    {
        return $this->hasMany('App\Models\Order_detail', 'order_header_id', 'order_header_id');
    }
}
