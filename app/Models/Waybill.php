<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waybill extends Model
{
    use HasFactory;
    protected $fillable = [
        'waybill_no', 'waybill_date', 'waybill_status', 'waybill_type',
        'routeto_branch_id', 'charter_route_id', 'car_id', 'driver_id', 'branchcar_id', 'waybill_payable',
        'waybill_amount', 'waybill_date', 'waybill_income', 'branch_car_rate', 'branch_car_income',
        'loader_id', 'departure_at', 'arrival_at', 'arrivaled_at', 'user_id', 'updated_by', 'branch_id', 'branch_rec_id'
    ];
    protected $casts = [
        'waybill_date' => 'date',
        'departure_at' => 'datetime',
        'arrival_at' => 'datetime',
        'arrivaled_at' => 'datetime'
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
        return $this->belongsTo('App\Models\User', 'loader_id',);
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
    public function order_headers()
    {
        return $this->hasMany('App\Models\Order_header');
    }

    public function waybill_statuses()
    {
        return $this->hasMany('App\Models\Waybill_status');
    }
    public function scopeConfirmed($query)
    {
        return $query->whereNotIn('waybill_status', ['loading', 'cancel']);
    }
    public function scopeLoading($query)
    {
        return $query->where('waybill_status', 'loading');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
    public function to_branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_rec_id');
    }
}
