<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_rec extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_status',  'sender_id', 'branchpay_by', 'bankaccount_id', 'bankreference', 'receipt_flag', 'receipt_id',
        'order_recname', 'idcardno', 'payment_status'
    ];
    protected $table = 'order_headers';
    protected $casts = [
        'order_header_date' => 'date'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
    public function to_customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_rec_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
    public function checker()
    {
        return $this->belongsTo('App\Models\User', 'checker_id');
    }
    public function loader()
    {
        return $this->belongsTo('App\Models\User', 'loader_id');
    }
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
    public function to_branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_rec_id');
    }

    public function order_details()
    {
        return $this->hasMany('App\Models\Order_detail', 'order_header_id');
    }

    public function order_statuses()
    {
        return $this->hasMany('App\Models\Order_status', 'order_header_id');
    }
    public function branchrec_waybill()
    {
        return $this->belongsTo('App\Models\Branchrec_waybill', 'waybill_id');
    }
}
