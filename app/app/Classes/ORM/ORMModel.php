<?php

namespace App\Classes\ORM;

use ReflectionClass;

abstract Class ORMModel
{
    protected $connection;

    protected $table;

    protected $query;

    public function __construct()
    {
        $this->connection = ORMConnection::instance();

        $this->query = new ORMQuery();
    }



    public function sqlHandler()
    {
        $query = $this->connection->sql()->prepare($this->prepare());

        $query->execute([]);

        return $query;
    }



    protected function getTable()
    {
        if(isset($this->table))
            return $this->table;
        
        $reflect = new ReflectionClass($this);

        $name = mb_strtolower(trim(preg_replace('~[A-Z]~u', '_$0', $reflect->getShortName()), '_'));

        $last = $name[-1];

        $name = mb_substr($name, 0, -1);
        
        if($last == 'y')
            $name.='ies';
        else
            $name.=$last.'s';

        return $name;
    }



    private function prepare()
    {
        $str = 'SELECT '.$this->query->getSelect().
            ' FROM '.
            $this->getTable().' '.
            $this->query->getJoin(). ' '.
            $this->query->getWhere().' '.
            $this->query->getOrder() .' '.
            $this->query->getLimit();

        return $str;
    }


    
    public function get()
    {
        $query = $this->sqlHandler();

        $data = $query->fetchAll();
        
        return $data;
    }



    public function count()
    {
        $this->query->clearQuery('select');

        $this->select('count(*) as _count');

        $query = $this->sqlHandler();

        $data = $query->fetchAll()[0]['_count'];
        
        return $data;
    }



    public function first()
    {
        $this->limit(1);

        $query = $this->sqlHandler();

        $data = $query->fetchAll()[0];
        
        return $data;
    }



    public function find($val)
    {
        $this->limit(1);

        $this->where('id', $val);

        $query = $this->sqlHandler();

        $data = $query->fetchAll()[0];
        
        return $data;
    }



    public function toSql()
    {
        return $this->prepare();
    }



    public function __call($name, $arguments)
    {
        $reflectionClass = new ReflectionClass($this->query);
        dump(func_get_args());
        dump($reflectionClass->getMethod($name));

        $this->query->$name($arguments);
        
        return $this;
    }



    public static function __callStatic($name, $arguments)
    {
        $me = new static;
        
        $me->query->$name($arguments);

        return $me;
    }
}