<?php

namespace App\Models\Traits;

trait AttachMethod
{
    public function attachMethod($relation, $data)
    {
        if(method_exists($this, $relation))
        {
            $this->$relation()->attach($data);
        }
    }



    public function syncMethod($relation, $data)
    {
        if(method_exists($this, $relation))
        {
            $this->$relation()->sync($data);
        }
    }
}
