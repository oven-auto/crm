<?php

namespace App\Classes\LadaDNM\Traits;

use Illuminate\Support\Facades\Log;

Trait LogTrait
{
    public function info($action, $entyType)
    {
        $message = 'Исполнено';
        
        $entyId = $this->getEntyId();

        $logMessage = 'Событие "'.$action.'". '.$message.'. '.$entyType.' '.$entyId.'.';
        
        Log::channel('dnm')->info($logMessage);
    }



    public function alert($action, $entyType)
    {
        $message = join(', ',$this->searchMessage($this->response->json()));
        
        $entyId = $this->getEntyId();

        $logMessage = 'Событие "'.$action.'" завершилось неудачей. '.$message.'. '.$entyType.' '.$entyId.'.';
        
        Log::channel('dnm')->alert($logMessage);
    }


    
    public function log(bool $result) : void
    {
        $action = '';
        $entyType = $this->getEntyType();

        if($this->response instanceof \Illuminate\Http\Client\Response)
        {
            $method = ($this->response->handlerStats()['effective_method']);
            
            $action = match($method){
                'POST' => 'Создание '.$entyType,
                'PUT'  => 'Изменение '.$entyType,
                default => '',
            };
        }
        if($result == 1)
            $this->info($action, $entyType);
        else
            $this->alert($action, $entyType);        
    }



    private function searchMessage(array $arr, array &$res = [])
    {
        foreach($arr as $key => $item)
            if($key == 'message')
                $res[] = $item;
            elseif(is_array($item))
                $this->searchMessage($item, $res);
        
        return $res;
    }
}