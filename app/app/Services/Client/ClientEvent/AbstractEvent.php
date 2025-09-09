<?php

namespace App\Services\Client\ClientEvent;

abstract class AbstractEvent
{
    protected Event $service;

    public function __construct()
    {   
        $this->service = new Event();
    }
}