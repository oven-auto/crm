<?php

namespace App\Classes\SMExpert;

use Illuminate\Support\Facades\Http;

Class Token {
    protected static $instance;

    //private static $login = 'oven-g-syktyvkar-sandbox';
    //private static $password = 'fUY2CluQLmCK5dUarfDf';
    // private static $login = 'client4434';
    // private static $password = 'Y6wo7tT3sz7gMeAmAr8X';

    private static $url = 'https://lk.cm.expert/oauth/token';
    private static $token;

    private function __construct(){}
    // private function __clone(){}
    // private function __wakeup(){}

    public static function getInstance()
    {
        if(self::$instance !== null)
            return self::$instance;
        return new self;
    }

    public function token()
    {
        $response = Http::withBasicAuth(env('CME_LOGIN'), env('CME_PASS'))
            ->post(self::$url, [
                'grant_type' => 'client_credentials'
            ]);

        $token = $response['token_type'].' '.$response['access_token'];

        self::$token = $token;
        return self::$token;
    }

    public function getToken()
    {
        return self::$token ?? $this->token();
    }
}
