<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner_option extends Model
{
    use HasFactory;
    protected $fillable = [
        'month_income', 'income_ratio', 'remark', 'user_id', 'updated_by'
    ];

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
