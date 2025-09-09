<?php

namespace App\Services\Trafic;

use App\Models\Trafic;
use App\Models\TraficLink;
use App\Services\GetShortCutFromURL\GetShortCutFromURL;
use Illuminate\Support\Facades\Auth;

Class SaveTraficLink extends AbstractTraficSaveService
{
    public function action(Trafic $trafic, array $data)
    {
        if(!isset($data['url']))
            return;
        
        $data = $data['url'];

        if(!$data)
            return;

        $trafic->links()->create([
            'author_id' =>Auth::id(),
            'text' => $data,
            'icon' => GetShortCutFromURL::get($data),
        ]);
    }
}