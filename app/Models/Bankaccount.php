<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bankaccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'bank_id', 'branch_id', 'account_no', 'account_name', 'account_type', 'bankbranch', 'user_id', 'updated_by', 'defaultflag'
    ];

    public function bank()
    {
        return $this->belongsTo('App\Models\Bank');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
}
