<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_branch extends Model
{
    use HasFactory;
    protected $table = 'order_headers';
    protected $fillable = [
        'order_header_no', 'order_header_date', 'order_status', 'branch_id', 'branch_rec_id',
        'customer_id', 'customer_rec_id', 'paymenttype', 'remark', 'waybill_id', 'trantype',
        'checker_id', 'loader_id', 'shipper_id', 'payment_status', 'order_amount', 'user_id', 'updated_by',
        'bankaccount_id', 'bankreference', 'created_at', 'branchpay_by', 'receipt_id', 'receipt_flag'
    ];
    protected $casts = [
        'order_header_date' => 'date',
        'created_at' => 'datetime',
    ];
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
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
        return $this->belongsTo('App\Models\Employee', 'loader_id');
    }

    public function shipper()
    {
        return $this->belongsTo('App\Models\Employee', 'shipper_id');
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

    public function waybill()
    {
        return $this->belongsTo('App\Models\Waybill', 'waybill_id');
    }
    public function receipt()
    {
        return $this->belongsTo('App\Models\Receipt', 'receipt_id');
    }
}
