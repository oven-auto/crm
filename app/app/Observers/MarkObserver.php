<?php

namespace App\Observers;
use Str;
use App\Models\Mark;

class MarkObserver
{
    public function saving(Mark $mark)
    {
    	$mark->slug = Str::slug($mark->name);
    }
}
