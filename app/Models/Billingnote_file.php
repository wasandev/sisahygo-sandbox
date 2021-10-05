<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billingnote_file extends Model
{
    use HasFactory;

    protected $fillable = ['billingnote_id', 'billingnote_files', 'file_description', 'user_id', 'updated_by'];

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
}
