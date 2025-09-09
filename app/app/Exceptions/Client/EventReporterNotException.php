<?php

namespace App\Exceptions\Client;

use Exception;

class EventReporterNotException extends Exception
{
    public function render()
    {
        return response()->json([
            "error" => true,
            "message" => 'Вас нет в списке отчитавшихся.',
            'success' => 0,
            'error' => implode(', ', [
                'Фаил где поймал исключение: '.$this->getFile(),
                'Cтрока с исключением: '.$this->getLine(),
            ])
        ], 404);
    }
}
