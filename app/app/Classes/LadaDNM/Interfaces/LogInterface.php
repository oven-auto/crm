<?php

namespace App\Classes\LadaDNM\Interfaces;

Interface LogInterface
{
    public function getEntyType() :string;

    public function log(bool $result) : void;

    public function getEntyId() : string;
}