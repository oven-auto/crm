<?php

namespace App\Services\Comment;

Class AbstractComment
{
    protected $data;

    public function text(string $message)
    {
        return array_merge($this->data, [
            'text' => $message
        ]);
    }

    public function getData()
    {
        $obj = new \stdClass();
        foreach( $this->data as $key => $value )
            $obj->$key = $value;
        return $obj;
    }
}
