<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'status', 'invoice_no', 'invoice_date', 'due_date', 'description', 'user_id', 'updated_by', 'billed'];
    protected $casts = ['invoice_date' => 'date', 'due_date' => 'date'];

    public function ar_customer()
    {
        return $this->belongsTo('App\Models\Ar_customer', 'customer_id');
    }

    /**
     * Get the user that owns the Invoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    /**
     * Get all of the ar_balances for the Invoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ar_balances()
    {
        return $this->hasMany(Ar_balance::class, 'invoice_id');
    }

    /**
     * Get the receipt_ar that owns the Invoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receipt_ar()
    {
        return $this->belongsTo(Receipt_ar::class, 'receipt_id');
    }
    // public function invoices()
    // {
    //     return $this->hasMany(Invoice::class, 'customer_id');
    // }
}
