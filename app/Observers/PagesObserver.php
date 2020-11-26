<?php

namespace App\Observers;

use App\Models\Page;

class PagesObserver
{
    public function creating(Page $page)
    {
        $page->user_id = auth()->user()->id;
    }
    public function updating(Page $page)
    {
        $page->updated_by = auth()->user()->id;
    }
}
