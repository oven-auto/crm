<?php

namespace App\Services\Trafic;

use App\Models\Trafic;

Abstract Class AbstractTraficSaveService
{
    abstract protected function action(Trafic $trafic, array $data);

    public static function save(Trafic $trafic, array $data)
    {
        $class = get_called_class();
        $me = new $class;
        $me->action($trafic, $data);
    }
}