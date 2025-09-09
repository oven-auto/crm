<?php

namespace App\Classes\Socket\Auth;

Class WSAuth
{
    private $authId;
    private $conn;

    private static $_instance = null;

    private function __construct(){}

    static public function getInstance() {
        if(is_null(self::$_instance))
            self::$_instance = new self();
       
        return self::$_instance;
    }



    public function setAuth(int $id)
    {
        $this->authId = $id;
    }



    public function getAuth()
    {
        return $this->authId;
    }



    public function setConn(\Ratchet\ConnectionInterface $conn)
    {
        $this->conn = $conn;
    }



    public function getConn()
    {
        return $this->conn;
    }
}