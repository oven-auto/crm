<?php

namespace App\Repositories\Bodywork;

use App\Models\BodyWork;

class BodyworkRepository
{
    public function save(BodyWork $bodyWork, array $data)
    {
        $arr['name'] = $data['name'];
        if (!$bodyWork->main)
            $arr['acronym'] = $data['acronym'];

        $bodyWork->fill($arr)->save();

        $bodyWork->vehiclebodies()->sync(['vehicle_id' => $data['vehicle']]);
    }
}
