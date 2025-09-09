<?php

namespace App\Models\Interfaces;

use app\Models\Interfaces\CarableInterface;
use App\Models\Worksheet;

interface HasActivityCarInterface
{
    public function getCar() : CarableInterface;

    public function getWorksheet() : Worksheet;
}