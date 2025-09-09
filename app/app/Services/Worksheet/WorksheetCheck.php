<?php

namespace App\Services\Worksheet;

use App\Http\Resources\Worksheet\WorksheetSaveResource;
use App\Models\Client;
use App\Models\Trafic;

Class WorksheetCheck
{
    /**
     * ПРОВЕРКА РАБОЧЕГО ЛИСТА ПРИ СОЗДАНИЕ НА НАЛИЧИЕ УЖЕ СОЗДАННОГО С ТАКИМИ ЖЕ ПАРАМЕТРАМИ
     */
    public static function check(Client $client, Trafic $trafic)
    {
        $worksheets = $client->open_worksheets;

        foreach ($worksheets as $item)
        {
            $isAppeal = $item->appeal->id == $trafic->appeal->id;
            $isCompany = $item->company_id == $trafic->company_id;
            $isStructure = $item->structure_id == $trafic->structure->id;

            if($isAppeal && $isCompany && $isStructure)
                return new WorksheetSaveResource($item);
        }

        return 0;
    }
}
