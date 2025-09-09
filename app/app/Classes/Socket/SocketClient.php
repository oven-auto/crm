<?php

namespace App\Classes\Socket;

Class SocketClient
{
    private $message;
    private $client;

    public function __construct($message = '')
    {
        $this->message = $message;
        $this->client = new \WebSocket\Client(env('WEBSOCKET_ADDRESS'));
    }

    public function send($message = '')
    {
        if($message)
            $this->message = $message;
        $this->client->text($this->message);
        $this->client->close();
    }

    public static function socket($message = '')
    {
        return new SocketClient($message);
    }
}



