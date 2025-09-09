<?php

namespace App\Services\Trafic;

use App\Models\Trafic;
use Illuminate\Support\Facades\Auth;

Class SaveTraficMessage extends AbstractTraficSaveService
{
    public function action(Trafic $trafic, array $data)
    {
        if(!isset($data['comment']) || $data['comment'] == '')
            return;

        if($trafic->message->message == $data['comment'])
            return;

        $trafic->message->fill([
            'author_id' => Auth::id(),
            'message' => $data['comment'],
        ])->save();
    }
}