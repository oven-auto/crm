<?php

namespace App\Classes\Vin;

use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

Class Vin
{
    public const REGEX =  '/^(?<wmi>[0-9A-HJ-NPR-Z]{3})(?<vds>[0-9A-HJ-NPR-Z]{6})(?<vis>[0-9A-HJ-NPR-Z]{8})$/';

    private $vin, $wmi, $vds, $vis, $region, $country, $fabric, $year;



    /**
     * VALIDATE VIN
     * @param string $vin
     * @return bool
     */
    public function validate(string $vin) : bool
    {
        $vin = strtoupper($vin);
        return preg_match(self::REGEX, $vin, $matches);
    }



    /**
     * PARSE VIN
     */
    public function parse(string $vin) : static
    {
        $vin = strtoupper($vin);

        if(!preg_match(self::REGEX, $vin, $matches))
            throw new \Exception('VIN is not valid');

        $this->vin          = $vin;
        $this->wmi          = $matches['wmi'];
        $this->vds          = $matches['vds'];
        $this->vis          = $matches['vis'];
        $this->fabric       = Fabric::decrypt($this->wmi);
        $this->year         = Year::decrypt($this->vis);
        $this->country      = Country::decrypt($this->wmi);
        $this->region       = Region::decrypt($this->wmi);

        return $this;
    }
}
