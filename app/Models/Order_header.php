<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_header extends Model
{
    protected $fillable = [
        'order_header_no', 'order_header_date', 'order_status', 'branch_id', 'branch_rec_id',
        'customer_id', 'customer_rec_id', 'paymenttype', 'remark', 'waybill_id', 'trantype',
        'checker_id', 'loader_id', 'shipper_id', 'payment_status', 'order_amount', 'user_id', 'updated_by',
        'bankaccount_id', 'bankreference', 'created_at', 'branchpay_by', 'tracking_no', 'total_weight', 'order_type',
        'shipto_center', 'useqrcode', 'ordercancel_id', 'order_recname', 'idcardno'
    ];
    protected $casts = [
        'order_header_date' => 'date',
        'created_at' => 'datetime',
        'canceled_at' => 'datetime',
    ];
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }
    public function to_customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_rec_id');
    }
    public function ordercancel()
    {
        return $this->belongsTo('App\Models\Order_header', 'ordercancel_id');
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
        return $this->hasMany('App\Models\Order_detail');
    }

    public function order_statuses()
    {
        return $this->hasMany('App\Models\Order_status');
    }

    public function waybill()
    {
        return $this->belongsTo('App\Models\Waybill');
    }
    // public function address()
    // {
    //     return $this->belongsTo('App\Models\Address', 'address_id');
    // }
    // public function to_address()
    // {
    //     return $this->belongsTo('App\Models\Address', 'to_address_id');
    // }

    public function scopeCash($query)
    {
        return $query->whereNotIn('order_status', ['checking', 'new', 'cancel'])
            ->where('paymenttype', 'H');
    }
    public function scopeTransfer($query)
    {
        return $query->whereNotIn('order_status', ['checking', 'new', 'cancel'])
            ->where('paymenttype', 'T');
    }
    public function scopeBranchcash($query)
    {
        return $query->whereNotIn('order_status', ['checking', 'new', 'cancel'])
            ->where('paymenttype', 'E');
    }
    public function scopeBill($query)
    {
        return $query->whereNotIn('order_status', ['checking', 'new', 'cancel'])
            ->whereIn('paymenttype', ['F', 'L']);
    }

    public function scopeGeneral($query)
    {
        return $query->whereNotIn('order_status', ['checking', 'new', 'cancel'])
            ->where('order_type', 'general');
    }
    public function scopeExpress($query)
    {
        return $query->whereNotIn('order_status', ['checking', 'new', 'cancel'])
            ->where('order_type', 'express');
    }
    public function scopeCharter($query)
    {
        return $query->whereNotIn('order_status', ['checking', 'new', 'cancel'])
            ->where('order_type', 'charter');
    }
    public function scopeOrder($query)
    {
        return $query->whereNotIn('order_status', ['checking', 'new', 'cancel']);
    }

    public function service_charges()
    {
        return $this->belongsToMany('App\Models\Service_charge', 'service_charges_order_header', 'order_header_id', 'service_charge_id')
            ->withPivot('service_amount', 'description');
    }
}
