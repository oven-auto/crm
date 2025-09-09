<?php

namespace App\Models\Traits;

trait Createable
{
    public function isCreate()
    {
        return $this->wasRecentlyCreated;
    }
}
