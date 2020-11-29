<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'car_regist',
        'status',
        'carimage',
        'car_province',
        'ownertype',
        'vendor_id',
        'engineno',
        'carno',
        'cartype_id',
        'carposition',
        'carstyle_id',
        'carbrand',
        'carmodel',
        'purchase_date',
        'purchase_price',
        'registration_date',
        'tiretype_id',
        'tires',
        'car_cc',
        'car_volumn',
        'car_weight',
        'load_weight',
        'fueltype',
        'saler_id',
        'insurance1_id',
        'insurance1_no',
        'insurance1_enddate',
        'insurance2_id',
        'insurance2_no',
        'insurance2_enddate',
        'finance_id',
        'user_id',
        'updated_by',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'registration_date' => 'date',
        'insurance1_enddate' => 'date',
        'insurance2_enddate' => 'date'
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
    public function cartype()
    {
        return $this->belongsTo('App\Models\Cartype');
    }


    public function carstyle()
    {
        return $this->belongsTo('App\Models\Carstyle');
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\Vendor', 'vendor_id');
    }
    public function seller()
    {
        return $this->belongsTo('App\Models\Vendor', 'saler_id');
    }

    public function financer()
    {
        return $this->belongsTo('App\Models\Vendor', 'finance_id');
    }

    public function tiretype()
    {
        return $this->belongsTo('App\Models\Tiretype');
    }
    public function province()
    {
        return $this->belongsTo('App\Models\Province',  'car_province', 'name');
    }

    public function waybills()
    {
        return $this->hasMany('App\Models\Waybill');
    }
}
