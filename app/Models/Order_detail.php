<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Order_detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_header_id', 'usepricetable', 'productservice_price_id', 'product_id', 'unit_id', 'price', 'amount',
        'remark', 'user_id', 'updated_by', 'weight'
    ];
    public function order_header()
    {
        return $this->belongsTo('App\Models\Order_header');
    }
    public function order_checker()
    {
        return $this->belongsTo('App\Models\Order_checker', 'order_header_id');
    }
    public function order_loader()
    {
        return $this->belongsTo('App\Models\Order_loader', 'order_header_id');
    }
    public function order_cash()
    {
        return $this->belongsTo('App\Models\Order_cash', 'order_header_id');
    }
    public function order_charter()
    {
        return $this->belongsTo('App\Models\Order_charter', 'order_header_id');
    }
    public function delivery_detail()
    {
        return $this->belongsTo('App\Models\Delivery_detail', 'order_header_id', 'order_header_id');
    }
    public function branch_balance()
    {
        return $this->belongsTo('App\Models\Branch_balance', 'order_header_id', 'order_header_id');
    }
    public function productservice_price()
    {

        return $this->belongsTo('App\Models\Productservice_price');
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\user');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
}
