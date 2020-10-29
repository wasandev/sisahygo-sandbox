<?php

namespace App\Observers;

use \Illuminate\Support\Facades\Auth;
use App\Models\Category;

class CategoryObserver
{
    public function creating(Category $category)
    {
        if (Auth::check()) {
            $category->user_id = auth()->user()->id;
        } else {
            $category->user_id = 1;
        }
    }

    public function updating(Category $category)
    {
        $category->updated_by = auth()->user()->id;
    }
}
