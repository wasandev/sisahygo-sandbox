<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_problem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_header_id', 'customer_flag', 'customer_id', 'contact_person', 'contact_phoneno', 'problem_no', 'problem_date', 'status', 'problem_type',
        'problem_detail', 'problem_claim', 'claim_amount', 'problem_personclaim',
        'problem_process', 'check_detail', 'discuss_detail', 'approve_amount',
        'order_amount_flag', 'user_id', 'checker_id', 'appprove_id', 'employee_id',
        'payment_date', 'payment_by', 'bankaccountname', 'bankaccount', 'bank_id', 'chequeno',
        'chequedate', 'chequebank_id'
    ];

    protected $casts = [
        'problem_date' => 'date',
        'payment_date' => 'date'
    ];

    /**
     * Get the order_header that owns the Order_problem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order_header()
    {
        return $this->belongsTo('App\Models\Order_header', 'order_header_id');
    }

    public function branchrec_order()
    {
        return $this->belongsTo('App\Models\Branchrec_order', 'order_header_id');
    }
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function checker()
    {
        return $this->belongsTo('App\Models\User', 'checker_id');
    }
    public function approve()
    {
        return $this->belongsTo('App\Models\User', 'approve_id');
    }
    public function employee()
    {
        return $this->belongsTo('App\Models\User', 'employee_id');
    }
    public function bank()
    {
        return $this->belongsTo('App\Models\Bank');
    }
    public function chequebank()
    {
        return $this->belongsTo('App\Models\Bank', 'chequebank_id');
    }


    /**
     * Get all of the order_problem_images for the Order_problem
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_problem_images()
    {
        return $this->hasMany(Order_problem_image::class);
    }
}
