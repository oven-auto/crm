<?php

namespace App\Classes\ORM;

use PDO;
use PDOException;

Class ORMConnection
{
    private $user;
    private $pass;
    private $host;
    private $port;
    private $driver;
    private $db;
    private static $me;

    private function __construct()
    {}



    public static function instance()
    {
        if(self::$me === null)
        {
            self::$me = new self;
            self::$me->connection();
        }
        return self::$me;
    }



    public function connection()
    {
        try{
            $this->db = new PDO('mysql:host=192.168.1.98;dbname=crm;port=3306', 'root', 'cesar', [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw $e;
        }
    }



    public function sql()
    {
        return $this->db;
    }
}