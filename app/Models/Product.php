<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = [
        'status',
        'name',
        'category_id',
        'product_style_id',
        'width',
        'length',
        'height',
        'weight',
        'unit_id',
        'user_id',
        'updated_by'
    ];


    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }
    public function product_images()
    {
        return $this->hasMany('App\Models\Product_image');
    }

    public function product_style()
    {
        return $this->belongsTo('App\Models\Product_style');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
    public function customer()
    {
        return $this->belongsToMany('App\Models\Customer')
            ->withTimestamps();
    }

    public function productservice_price()
    {
        return $this->hasMany('App\Models\Productservice_price', 'product_id');
    }
    public function productservice_newprice()
    {
        return $this->hasMany('App\Models\Productservice_newprice', 'product_id');
    }
    public function customer_product_prices()
    {
        return $this->hasMany('App\Models\Customer_product_price');
    }
    public function scopeCharter($query)
    {
        return $query->whereNotIn('order_status', ['checking', 'new', 'cancel'])
            ->where('order_type', 'charter');
    }
}
