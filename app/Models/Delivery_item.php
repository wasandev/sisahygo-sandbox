<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery_item extends Model
{
    use HasFactory;
    protected $fillable = [
        'delivery_id', 'customer_id', 'delivery_status', 'payment_status', 'user_id',
        'updated_by', 'receipt_id', 'payment_amount', 'discount_amount',
        'tax_amount', 'pay_amount', 'receipt_type', 'branchpay_by', 'bankaccount_id', 'bankreference',
        'chequeno', 'chequedate', 'chequebank_id', 'description', 'paydate'
    ];
    protected $casts = [
        'paydate' => 'date',
    ];

    public function delivery()
    {
        return $this->belongsTo('App\Models\Delivery');
    }


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }


    public function delivery_details()
    {
        return $this->hasMany('App\Models\Delivery_detail');
    }
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function receipt()
    {
        return $this->belongsTo('App\Models\Receipt');
    }
    // public function branch_balances()
    // {
    //     return $this->hasMany('App\Models\Branch_balance', 'delivery_id');
    // }
}
