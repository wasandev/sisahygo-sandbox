<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waybill extends Model
{
    use HasFactory;
    protected $fillable = [
        'waybill_no', 'waybill_date', 'waybill_status', 'waybill_type',
        'routeto_branch_id', 'charter_route_id', 'car_id', 'driver_id', 'branchcar_id',
        'waybill_amount', 'waybill_date', 'waybill_income', 'branch_car_rate', 'branch_car_income',
        'loader_id', 'departure_at', 'arrival_at', 'user_id', 'updated_by'
    ];
    protected $casts = [
        'waybill_date' => 'date'
    ];
    public function routeto_branch()
    {
        return $this->belongsTo('App\Models\Routeto_branch');
    }
    public function charter_route()
    {
        return $this->belongsTo('App\Models\Charter_route');
    }

    public function car()
    {
        return $this->belongsTo('App\Models\Car');
    }

    public function driver()
    {
        return $this->belongsTo('App\Models\Employee', 'driver_id');
    }

    public function branch_car()
    {
        return $this->belongsTo('App\Models\Car', 'branchcar_id');
    }

    public function loader()
    {
        return $this->belongsTo('App\Models\Employee', 'loader_id',);
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function order_loaders()
    {
        return $this->hasMany('App\Models\Order_loader');
    }
}
