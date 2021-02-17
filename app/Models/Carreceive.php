<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carreceive extends Model
{
    use HasFactory;
    protected $fillable = [
        'status', 'branch_id', 'type', 'receive_no', 'car_id', 'vendor_id',
        'receive_date', 'amount', 'receive_by', 'bankaccount_id',
        'frombankaccount', 'frombank_id', 'frombankaccountname', 'chequeno',
        'chequedate', 'chequebank_id', 'description',
        'user_id', 'updated_by',
    ];

    protected $casts = ['receive_date' => 'date'];

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
     * Get the Vendor that owns the Carpayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
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
    public function frombank()
    {
        return $this->belongsTo(Bank::class, 'frombank_id');
    }

    public function bankaccount()
    {
        return $this->belongsTo(Bankaccount::class, 'bankaccount_id');
    }
    public function chequebank()
    {
        return $this->belongsTo(Bank::class, 'chequebank_id');
    }
}
