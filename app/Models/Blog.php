<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = [
        'slug',
        'blog_cat_id',
        'user_id',
        'title',
        'blog_content',
        'blog_image',
        'published',
        'published_at',
        'updated_by'
    ];
    // protected $dateFormat = 'TH';
    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
}
