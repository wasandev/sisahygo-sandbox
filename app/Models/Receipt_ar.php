<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt_ar extends Model
{
    use HasFactory;
    protected $fillable = [
        'receipt_no', 'status', 'receipt_date', 'branch_id', 'customer_id', 'total_amount', 'discount_amount',
        'tax_amount', 'pay_amount', 'receipttype', 'branchpay_by', 'bankaccount_id', 'bankreference',
        'chequeno', 'chequedate', 'chequebank_id', 'description', 'user_id', 'updated_by'
    ];
    protected $table = 'receipts';
    protected $casts = [
        'receipt_date' => 'date'
    ];

    public function ar_customer()
    {
        return $this->belongsTo('App\Models\Ar_customer', 'customer_id');
    }

    public function bankaccount()
    {
        return $this->belongsTo('App\Models\Bankaccount');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice', 'receipt_id');
    }
    public function chequebank()
    {
        return $this->belongsTo('App\Models\Bank', 'chequebank_id');
    }
}
