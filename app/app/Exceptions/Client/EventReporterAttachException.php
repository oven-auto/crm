<?php

namespace App\Exceptions\Client;

use Exception;

class EventReporterAttachException extends Exception
{
    public function render()
    {
        return response()->json([
            "error" => true,
            "message" => 'Вы уже отчитались за данное действие.',
            'success' => 0,
            'error' => implode(', ', [
                'Фаил где поймал исключение: '.$this->getFile(),
                'Cтрока с исключением: '.$this->getLine(),
            ])
        ], 404);
    }
}
