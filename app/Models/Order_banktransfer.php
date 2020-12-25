<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_banktransfer extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_header_id', 'status', 'branch_id', 'transfer_amount', 'bankaccount_id', 'reference', 'transferslip', 'user_id', 'updated_by'
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
}
