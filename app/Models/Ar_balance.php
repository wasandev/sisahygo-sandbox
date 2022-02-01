<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ar_balance extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id', 'doctype', 'docno',
        'docdate', 'order_header_id', 'receipt_id', 'invoice_id',
        'description', 'ar_amount', 'user_id', 'updated_by', 'branch_id'
    ];

    protected $casts = [
        'docdate' => 'date',

    ];
    public function ar_customer()
    {
        return $this->belongsTo('App\Models\Ar_customer', 'customer_id');
    }

    public function order_header()
    {
        return $this->belongsTo('App\Models\Order_header');
    }
    public function order_charter()
    {
        return $this->belongsTo('App\Models\Order_charter', 'order_header_id');
    }
    public function receipt_ar()
    {
        return $this->belongsTo('App\Models\Receipt_ar', 'receipt_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice');
    }
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
}
