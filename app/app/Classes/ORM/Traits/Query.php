<?php

namespace App\Classes\ORM\Traits;

use App\Classes\ORM\ORMJoin;
use App\Classes\ORM\ORMLimit;
use App\Classes\ORM\ORMOrder;
use App\Classes\ORM\ORMSelect;
use App\Classes\ORM\ORMWhere;

trait Query
{
    /**
     * WHERE
     */
    public function where($col, $operand = null, $value = null)
    {
        dd();
        $where = new ORMWhere($arr);

        $this->queryArr['where'][] = ['logic' => 'and', 'data' => $where];
    }



    /**
     * OR WHERE
     */
    public function orWhere(array $arr)
    {
        $where = new ORMWhere($arr);

        $this->queryArr['where'][] = ['logic' => 'or', 'data' => $where];
    }



    /**
     * LIMIT
     */
    public function limit($val)
    {   
        $limit = new ORMLimit($val);

        $this->queryArr['limit'] = $limit;
    }



    /**
     * SELECT
     */
    public function select(array $arr)
    {
        $select = new ORMSelect($arr);

        $this->queryArr['select'][] = $select;
    }



    /**
     * ORDER BY
     */
    public function orderBy(array $arr)
    {
        $order = new ORMOrder($arr);

        $this->queryArr['order'][] = $order;
    }



    /**
     * LEFT JOIN
     */
    public function leftJoin(array $arr)
    {
        $join = new ORMJoin($arr);

        $join->setType('left');

        $this->queryArr['join'][] = $join;
    }
}