<?php

namespace App\Observers;

use App\Classes\Telegram\Telegram;
use App\Jobs\TelegramJob;
use App\Models\Trafic;

class TraficObserver
{
    public function updating(\App\Models\Trafic $trafic)
    {
        $current = $trafic->trafic_status_id;

        $old = $trafic->getOriginal('trafic_status_id');

        if ($current != $old && ($current == 4 || $current == 3))
            $trafic->processing_at = now();
    }



    public function saving(\App\Models\Trafic $trafic)
    {
        $currentMn = $trafic->manager_id;

        $oldMn = $trafic->getOriginal('manager_id');

        if(!$trafic->manager_id)
            if($trafic->company_id == null)
                $trafic->trafic_status_id = 6;
        
        if ($currentMn != $oldMn && $trafic->trafic_status_id != 4)
            $trafic->trafic_status_id = 2;
    }



    public function saved(Trafic $trafic)
    {
        if($trafic->trafic_status_id == $trafic->getOriginal('trafic_status_id'))
            return;

        $array = match($trafic->trafic_status_id) {
            1 => ['action' => 'waiting',  'users'     => $trafic->usersIdByTraficAppeal(1)],
            2 => ['action' => 'assign',   'users'     => [$trafic->manager_id]],
            3 => ['action' => 'confirm',  'users'     => [$trafic->author_id]],
            4 => ['action' => 'confirm',  'users'     => [$trafic->author_id]],
            5 => ['action' => 'confirm',  'users'     => [$trafic->author_id]],
            default => '',
        };
        
        if(!is_array($array))
            return;
        
        $notice = new \App\Classes\Telegram\Notice\TelegramNotice();
        
        $method = $array['action'];

        $notice->set($trafic)->$method()->send($array['users']);
    }

    

    public function deleting(\App\Models\Trafic $trafic)
    {
        $trafic->trafic_status_id = 5;

        $trafic->processing_at = now();
    }



    public function creating(\App\Models\Trafic $trafic)
    {
        if(!$trafic->manager_id && $trafic->company_id)
            $trafic->trafic_status_id = 1;
    }



    public function updated(\App\Models\Trafic $trafic)
    {
       
    }
}
