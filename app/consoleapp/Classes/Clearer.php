<?php

namespace SH\Classes;

use Sh\Interfaces\HandlerInterface;

Class Clearer implements HandlerInterface
{
    public function handler()
    {
        system("clear");
    }
}