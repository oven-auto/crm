<?php

namespace App\Services\Car\CarGeneral;

abstract Class AbstractCarGeneral
{
    public $id;
    public $vin;
    public $brand;
    public $model;
    public $bodywork;
    public $vehicle;
    public $size;
    public $power;
    public $transmission;
    public $driver;
    public $type;
    public $year;
    public $typename;

    public function toArray()
    {
        return (array) $this;
    }
}