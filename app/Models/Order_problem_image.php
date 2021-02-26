<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_problem_image extends Model
{
    use HasFactory;

    protected $fillable = ['order_problem_id', 'problemimage', 'problemfile'];

    /**
     * Get the order_problem that owns the Order_problem_image
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order_problem()
    {
        return $this->belongsTo(Order_problem::class);
    }
}
