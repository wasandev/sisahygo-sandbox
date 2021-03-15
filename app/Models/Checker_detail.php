<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order_checker;

class Checker_detail extends Model
{
    use HasFactory;
    protected $table = 'order_details';

    protected $fillable = [
        'order_header_id', 'usepricetable', 'productservice_price_id', 'product_id', 'unit_id', 'price', 'amount',
        'remark', 'user_id', 'updated_by', 'weight'
    ];

    public function order_checker()
    {
        return $this->belongsTo('App\Models\Order_checker', 'order_header_id');
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
