<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car_balance extends Model
{
    use HasFactory;
    protected $fillable = [
        'car_id', 'vendor_id', 'doctype', 'docno', 'description',
        'amount', 'user_id', 'updated_by', 'cardoc_date', 'waybill_id', 'carpayment_id',
        'carreceive_id'
    ];

    protected $casts = ['cardoc_date' => 'date'];

    /**
     * Get the user that owns the Car_balance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the car that owns the Car_balance
     *
     * @return \Illuminate\Carbase\Eloquent\Relations\BelongsTo
     */
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the user_update that owns the Car_balance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user_update()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the waybill that owns the Car_balance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function waybill()
    {
        return $this->belongsTo(Waybill::class);
    }
    /**
     * Get the carpayment that owns the Car_balance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carpayment()
    {
        return $this->belongsTo(Carpayment::class);
    }

    /**
     * Get the carreceive that owns the Car_balance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carreceive()
    {
        return $this->belongsTo(Carreceive::class);
    }
}
