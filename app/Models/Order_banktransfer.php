<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_banktransfer extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id', 'order_header_id', 'status', 'transfer_type', 'branch_id', 'transfer_amount',
        'bankaccount_id', 'reference', 'transferslip', 'user_id', 'updated_by', 'receipt_id', 'invoice_id',
        'tax_amount', 'discount_amount', 'transfer_date'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
    public function bankaccount()
    {
        return $this->belongsTo('App\Models\Bankaccount');
    }
    public function order_header()
    {
        return $this->belongsTo('App\Models\Order_header');
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

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }
    public function receipt_all()
    {
        return $this->belongsTo('App\Models\Receipt_all', 'receipt_id');
    }

    /**
     * Get all of thn Order_banktransfers for the Order_banktransfer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_banktransfer_items()
    {
        return $this->hasMany('App\Models\Order_banktransfer_item');
    }
}
