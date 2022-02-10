<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carpayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'status', 'branch_id', 'type', 'payment_no', 'car_id', 'vendor_id',
        'payment_date', 'amount', 'payment_by', 'bankaccount_id',
        'tobankaccount', 'tobank_id', 'tobankaccountname', 'chequeno',
        'chequedate', 'chequebank_id', 'tax_flag', 'waybill_id',
        'tax_amount', 'description',
        'user_id', 'updated_by',
    ];

    protected $casts = ['payment_date' => 'date'];
    /**
     * Get the car that owns the Carpayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    /**
     * Get the branch that owns the Carpayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    /**
     * Get the Vendor that owns the Carpayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the user that owns the Carpayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user_update that owns the Carpayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user_update()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    /**
     * Get the bank that owns the Carpayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tobank()
    {
        return $this->belongsTo(Bank::class, 'tobank_id');
    }

    public function bankaccount()
    {
        return $this->belongsTo(Bankaccount::class, 'bankaccount_id');
    }
    public function chequebank()
    {
        return $this->belongsTo(Bank::class, 'chequebank_id');
    }

    public function branchrec_waybill()
    {
        return $this->belongsTo(Branchrec_waybill::class, 'waybill_id');
    }
    public function waybill()
    {
        return $this->belongsTo(Waybill::class, 'waybill_id');
    }
}
