<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;

class Ar_customer extends Model
{

    protected $fillable = [
        'status',
        'type',
        'user_id',
        'customer_code',
        'taxid',
        'email',
        'paymenttype',
        'creditterm',
        'name',
        'address',
        'sub_district',
        'district',
        'province',
        'postal_code',
        'country',
        'description',
        'contractname',
        'imagefile',
        'logofile',
        'phoneno',
        'weburl',
        'facebook',
        'line',
        'location_lat',
        'location_lng',
        'businesstype_id',
        'user_id',
        'updated_by'
    ];
    protected $table = 'customers';
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
    public function assign_customer()
    {
        return $this->hasOne('App\Models\User');
    }
    public function businesstype()
    {
        return $this->belongsTo('App\Models\Businesstype');
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\Address');
    }

    public function product()
    {
        return $this->belongsToMany('App\Models\Product')
            ->withTimestamps();
    }

    public function customer_product_prices()
    {
        return $this->hasMany('App\Models\Customer_product_price');
    }
    public function order_sends()
    {
        return $this->hasMany('App\Models\Order_header', 'customer_id', 'id');
    }
    public function order_recs()
    {
        return $this->hasMany('App\Models\Order_header', 'customer_rec_id', 'id');
    }

    public function ar_balances()
    {
        return $this->hasMany('App\Models\Ar_balance', 'customer_id');
    }
    public function receipt_ars()
    {
        return $this->hasMany('App\Models\Receipt_ar', 'customer_id');
    }
    /*
	Provide the Location value to the Nova field
	*/
    public function getLocationAttribute()
    {
        return (object) [
            'latitude' => $this->location_lat,
            'longitude' => $this->location_lng,
        ];
    }


    /*
	Transform the returned value from the Nova field
	*/
    public function setLocationAttribute($value)
    {
        $location_lat = round(object_get($value, 'latitude'), 7);
        $location_lng = round(object_get($value, 'longitude'), 7);
        $this->attributes['location_lat'] = $location_lat;
        $this->attributes['location_lng'] = $location_lng;
    }
}
