<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pricezone extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function districts()
    {
        return $this->belongsToMany('App\Models\District')->withPivot('express_fee', 'faraway_fee');
    }
}
