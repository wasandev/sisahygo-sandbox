<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_checker extends Model
{
    protected $fillable = [
        'order_header_date', 'order_status', 'branch_id', 'branch_rec_id',
        'customer_id', 'customer_rec_id', 'paymenttype', 'remark',  'trantype',
        'checker_id', 'order_amount', 'user_id', 'updated_by', 'useqrcode'
        //, 'use_address', 'address_id',
        //'use_to_address', 'to_address_id'
    ];
    protected $table = 'order_headers';

    protected $casts = [
        'order_header_date' => 'date'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }
    public function to_customer()
    {
        //$branch_area = \App\Models\Branch_area::where('branch_id', $this->branch_rec_id);
        return $this->belongsTo('App\Models\Customer', 'customer_rec_id');
        //->whereIn('district', $branch_area);
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

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
    public function to_branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_rec_id');
    }

    public function checker_details()
    {
        return $this->hasMany('App\Models\Checker_detail', 'order_header_id');
    }

    public function order_statuses()
    {
        return $this->hasMany('App\Models\Order_status', 'order_header_id');
    }
}
