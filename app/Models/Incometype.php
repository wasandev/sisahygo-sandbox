<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incometype extends Model
{
    use HasFactory;
    protected $fillable = [
        'code', 'name', 'payertype', 'taxrate', 'taxform', 'user_id',
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
}
