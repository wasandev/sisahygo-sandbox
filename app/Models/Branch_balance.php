<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch_balance extends Model
{
    use HasFactory;
    protected $fillable = [
        'branchbal_date', 'branchpay_date', 'branch_id', 'customer_id',
        'bal_amount', 'discount_amount', 'tax_amount', 'pay_amount', 'user_id', 'payment_status',
        'updated_by', 'remark', 'receipt_id', 'type', 'order_header_id', 'delivery_id'
    ];

    protected $casts = [
        'branchbal_date' => 'date',
        'branchpay_date' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function user_update()
    {
        return $this->belongsTo('App\models\User', 'updated_by');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function receipt()
    {
        return $this->belongsTo('App\Models\Receipt');
    }
    public function branchrec_order()
    {
        return $this->belongsTo('App\Models\Branchrec_order', 'order_header_id');
    }


    public function delivery()
    {
        return $this->belongsTo(Delivery::class, 'delivery_id');
    }
}
