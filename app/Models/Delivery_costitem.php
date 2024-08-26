<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery_costitem extends Model
{
    use HasFactory;
    protected $fillable = [
        'delivery_id', 'company_expense', 'employee_id',  'description','personal_costs', 'amount', 'user_id',
        'updated_by'
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function company_expense()
    {
        return $this->belongsTo('App\Models\Company_expense');
    }
    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }

    public function delivery()
    {
        return $this->belongsTo('App\Models\Delivery');
    }
    
}
