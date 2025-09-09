<?php

namespace App\Exceptions;

use App\Exceptions\Redemption\RedemptionException;
use App\Exceptions\Reserve\ReserveException;
use App\Jobs\TelegramJob;
use App\Models\TelegramConnection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }



    public function sendTelegram(Throwable $exception)
    {       
        $options = [
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
        ];

        $user = TelegramConnection::select('user_id')
            ->leftJoin('users', 'users.telegram_connection_id', 'telegram_connections.id')
            ->where('users.id', 47)
            ->first();

        $message = [
            '**************************',
            '',
            'Дата: '. now()->format('d.m.Y H:i'),
            '',
            'Инициатор: '.Auth::user()->cut_name,
            '',
            'Сообщение:' .$exception->getMessage(), 
            'Фаил где поймал исключение: '.$exception->getFile(),
            'Cтрока с исключением: '.$exception->getLine(),
            '**************************',
        ];

        $message = implode("\n", $message);

        (TelegramJob::dispatch($user->user_id, $message, $options));
    }



    public function render($request, Throwable $exception)
    {
        //$this->sendTelegram($exception);
        
        if(
            $exception instanceof ReserveException ||
            $exception instanceof RedemptionException
        //     $exception instanceof \App\Exceptions\Client\EventExcecutorAppendException ||
        //     $exception instanceof \App\Exceptions\Client\EventExcecutorDetachException ||
        //     $exception instanceof \App\Exceptions\Client\EventReporterAttachException ||
        //     $exception instanceof \App\Exceptions\Client\EventReporterIsAuthorException ||
        //     $exception instanceof \App\Exceptions\Client\EventReporterNotException ||
        //     $exception instanceof \App\Exceptions\Client\EventCloseIsWorking ||
        //     $exception instanceof \App\Exceptions\Client\EventCloseNotWhileIsNew
        )
        {
            return $exception->render();
        }

        return response()->json([
            'message' => 'Ошибка: '.$exception->getMessage(),
            'success' => 0,
            'error' => implode(', ', [
                'Фаил где поймал исключение: '.$exception->getFile(),
                'Cтрока с исключением: '.$exception->getLine(),
            ])
        ], 404);
    }
}
