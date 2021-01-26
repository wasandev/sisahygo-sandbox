<?php

namespace App\Observers;

use App\Models\Blog;
use App\Traits\ThaiSlug;

class BlogsObserver
{
    use ThaiSlug;
    public function creating(Blog $blog)
    {
        $blog->user_id = auth()->user()->id;
        $blog->slug = $this->convertToSlug($blog->title);
    }

    public function updating(Blog $blog)
    {
        $blog->updated_by = auth()->user()->id;
    }
}
