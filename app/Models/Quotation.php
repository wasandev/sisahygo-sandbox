<?php

namespace App\Models;

use App\Nova\Quotation as AppQuotation;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{

    const OPEN_STATUS = 'Open';
    const EDIT_STSTUS = 'Edit';
    const COMFIRM_STATUS = 'Comfirm';
    const REJECT_STATUS = 'Reject';

    protected $fillable = [
        'active', 'status', 'quotation_no', 'quotation_date', 'branch_id', 'customer_id', 'paymenttype', 'terms', 'expiration_date', 'user_id', 'updated_by',
        'duedate', 'delivery_date', 'remark'

    ];

    protected $casts = [
        'quotation_date' => 'datetime',
        'expiration_date' => 'datetime',
        'duedate' => 'date',
        'delivery_date' => 'date'
    ];
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
    public function charter_prices()
    {
        return $this->belongsToMany('App\Models\Charter_price', 'charter_price_quotation', 'quotation_id', 'charter_price_id')
            ->withPivot('product_id', 'description', 'unit_id', 'product_amount', 'product_weight', 'charter_amount');
    }
}
