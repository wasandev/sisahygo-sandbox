<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiClient extends Model
{
    protected $table = 'api_clients';

    protected $fillable = [
        'name',
        'customer_id',
        'api_key',                // เก็บเป็น hash
        'is_active',
        'allowed_ips',
        'rate_limit_per_minute',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
