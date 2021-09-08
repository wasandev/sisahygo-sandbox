<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withholdingtax extends Model
{
    use HasFactory;

    protected $fillable = ['pay_date', 'payertype', 'vendor_id', 'incometype_id', 'pay_amount', 'tax_amount', 'user_id', 'updated_by'];
    protected $casts = [
        'pay_date' => 'date'
    ];


    public function vendor()
    {
        return $this->belongsTo('App\Model\Vendor');
    }
    public function incometype()
    {
        return $this->belongsTo('App\Model\Incometype');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
}
