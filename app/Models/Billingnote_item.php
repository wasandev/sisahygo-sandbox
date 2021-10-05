<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billingnote_item extends Model
{
    use HasFactory;
    protected $fillable = ['billingnote_id', 'invoice_id', 'user_id', 'updated_by'];

    /**
     * Get the user that owns the Billingnote_item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function billingnote()
    {
        return $this->belongsTo('App\Models\Billingnote');
    }
    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice');
    }
}
