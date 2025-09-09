<?php

namespace App\Exceptions\Client;

use Exception;

class EventReporterIsAuthorException extends Exception
{
    public function render()
    {
        return response()->json([
            "error" => true,
            "message" => 'Вы автор. Автор не может отчитываться.',
            'success' => 0,
            'error' => implode(', ', [
                'Фаил где поймал исключение: '.$this->getFile(),
                'Cтрока с исключением: '.$this->getLine(),
            ])
        ], 404);
    }
}
