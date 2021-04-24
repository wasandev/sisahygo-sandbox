<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dropship_tran extends Model
{
    use HasFactory;
    protected $fillable = [
        'status', 'branch_id', 'dropship_tran_no', 'dropship_tran_date',
        'employee_id', 'tran_amount', 'dropship_income', 'scash_amount', 'dcash_amount',
        'user_id', 'updated_by'
    ];

    protected $casts = [
        'dropship_tran_date' => 'date'
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }

    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }


    public function order_dropships()
    {
        return $this->hasMany('App\Models\Order_dropship');
    }
}
