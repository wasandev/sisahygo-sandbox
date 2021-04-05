<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charter_price extends Model
{

    protected $fillable = [
        'cartype_id', 'carstyle_id', 'charter_route_id',  'status', 'price', 'pickuppoint', 'overpointcharge', 'fuel_cost', 'fuel_amount', 'timespent',  'car_charge', 'driver_charge', 'user_id', 'updated_by'
    ];

    public function charter_route()
    {
        return $this->belongsTo('App\Models\Charter_route');
    }

    public function cartype()
    {
        return $this->belongsTo('App\Models\Cartype');
    }

    public function carstyle()
    {
        return $this->belongsTo('App\Models\Carstyle');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    /**
     * The quotations that belong to the Charter_price
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function quotations()
    {
        return $this->belongsToMany(Quotation::class, 'charter_price_quotation', 'quotation_id', 'charter_price_id')
            ->withPivot('product_id', 'description', 'unit_id', 'product_amount', 'product_weight', 'charter_amount');
    }
}
