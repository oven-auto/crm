<?php

namespace App\Services\Trafic;

use App\Models\Trafic;
use Carbon\Carbon;

Class SaveTraficControl extends AbstractTraficSaveService
{
    private const DATA_KEYS = ['begin_at', 'end_at'];

    private const DATA_FORMAT = 'd.m.Y H:i';

    private $begin_at;
    private $end_at;
    private $interval;

    private function setDate(array $data)
    {
        foreach($data as $key => $item)
            if(in_array($key, self::DATA_KEYS))
                $this->$key = Carbon::createFromFormat(self::DATA_FORMAT, $item);
        $this->interval = $this->end_at->diffInMinutes($this->begin_at);
    }



    protected function action(Trafic $trafic, array $data)
    {
        if(!isset($data['begin_at']) || !isset($data['end_at']))
            return;

        $this->setDate($data);

        $trafic->control->fill([
            'end_at' => $this->end_at,
            'begin_at' => $this->begin_at,
            'interval' => $this->interval,
        ])->save();
    }
}