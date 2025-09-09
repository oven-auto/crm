<?php

namespace App\Observers;

use App\Models\Brand;
use Str;

class BrandObserver
{
    public function saving(Brand $brand)
    {
    	$brand->slug = Str::slug($brand->name);
    }
}
