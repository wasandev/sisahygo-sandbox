<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Billingnote extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = ['status', 'billingnote_date', 'customer_id', 'billing_by', 'user_id', 'updated_by', 'set_payment_date'];

    protected $casts = [
        'billingnote_date' => 'datetime',
        'set_payment_date' => 'datetime',
    ];
    public function ar_customer()
    {
        return $this->belongsTo('App\Models\Ar_customer', 'customer_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function billingnote_items()
    {
        return $this->hasMany('App\Models\Billingnote_item');
    }
    public function billingnote_files()
    {
        return $this->hasMany('App\Models\Billingnote_file');
    }

    public function routeNotificationForMail($notification)
    {
        // Return email address only...
        //dd($this->ar_customer->email);
        return $this->ar_customer->email;
    }
}
