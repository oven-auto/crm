<?php

namespace App\Classes\ORM;

use App\Classes\ORM\Traits\Query;

/**
 * CLASS ORMQuery
 */
Class ORMQuery
{
    use Query;

    private $queryArr;

    public function clearQuery(string $key)
    {
        unset($this->queryArr[$key]);
    }



    public function getLimit()
    {
        if($this->queryArr['limit'])
            return $this->queryArr['limit']->makeString();
    }



    public function getWhere()
    {
        if(!isset(($this->queryArr['where'])))
            return;

        foreach($this->queryArr['where'] as $key => $item)
            $str[] = match($key) {
                0 => $item['data']->makeString(),
                default => $item['logic'].' '.$item['data']->makeString(),
            };
        
        $str = implode(' ', $str);

        return 'WHERE '. $str;
    }



    public function getSelect()
    {
        $arr = $this->queryArr['select'] ?? new ORMSelect(['*']);

        foreach($arr as $item)
            $str[] = $item->makeString();

        return implode(', ', $str);
    }



    public function getOrder()
    {
        if(!isset($this->queryArr['order']))
            return;
        
        foreach($this->queryArr['order'] as $item)
            $str[] = $item->makeString();

        return 'order by ' . implode(', ', $str);
    }



    public function getJoin()
    {
        if(!isset($this->queryArr['join']))
            return;

        foreach($this->queryArr['join'] as $item)
            $str[] = $item->makeString();
        
        return implode(' ', $str);
    }
}