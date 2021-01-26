<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt_item extends Model
{
    use HasFactory;
    protected $fillable = [
        'receipt_id', 'order_header_id', 'user_id', 'updated_by'
    ];

    public function receipt()
    {
        return $this->belongsTo('App\Models\Receipt');
    }
    public function receipt_all()
    {
        return $this->belongsTo('App\Models\Receipt_all', 'receipt_id');
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
}
