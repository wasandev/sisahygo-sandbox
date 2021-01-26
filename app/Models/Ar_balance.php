<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ar_balance extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'order_header_id', 'receipt_id', 'description', 'ar_amount', 'user_id', 'updated_by'];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function order_header()
    {
        return $this->belongsTo('App\Models\Order_header');
    }
    public function receipt()
    {
        return $this->belongsTo('App\Models\Receipt');
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
