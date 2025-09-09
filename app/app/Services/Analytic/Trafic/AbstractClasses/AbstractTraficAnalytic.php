<?php

namespace App\Services\Analytic\Trafic\AbstractClasses;

use App\Http\Filters\TraficAnalyticFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Database\Query\Builder;

abstract class AbstractTraficAnalytic
{ 
    protected  $filters = [];
    protected  $queries = [];
    protected  $base;
    
    abstract protected function prepareQuery(&$q, $a, $p);
    
    
    
    /**
     * Подготовить фильтры для каждого из интервалов
     * @param array $array
     */
    protected function makeFilters(array $array) : void
    {
        foreach ($array as $item)
            $this->filters[] = app()->make(TraficAnalyticFilter::class, ['queryParams' => array_filter($item)]);
    }
    
    
    
    /**
     * Подготовить подзапросы на каждый из интервалов
     */
    protected function makeQuery() : void
    {
        if(!($this->base instanceof Builder))
            throw new \Exception('Базовый запрос не указан.');
        
        $this->queries = [];
        
        foreach ($this->filters as $key => $itemFilter)
        {
            $qTmp = clone $this->base;
            $this->queries[] = $this->prepareQuery($qTmp, $itemFilter, ++$key);
        }
    }
    
    
    
    /**
     * Выполнить поиск
     * @return Collection|NULL
     */
    public function getData() : Collection|null
    {
        if(count($this->queries))
        {
            foreach ($this->queries as $i => $item)
                if($i == 0)
                    $subQ = $item;
                else
                    $subQ->union($item);
            return $subQ->get();
        }
        
        return null;
    }
    
    
    
    /**
     * Сгруппировать по периоду
     * @param Collection $data
     * @return array
     */
    protected function group(Collection|null $data) : array
    {
        $returned = [];
        
        for($i=1; $i<=3; $i++)
            $returned[$i] = $data->filter(function($item) use($i){
                if($item['period'] == $i)
                    return  $item;
            });
                
        return $returned;
    }
    
    
    
    /**
     * Получить результат
     * @param array $arr
     * @return array|NULL[]
     */
    public function analytics(array $arr) : array|null
    {
        $this->makeFilters($arr);
        
        $this->makeQuery();
        
        $result = $this->getData();
        
        $result = $this->group($result);
        
        return  $result;
    }
}

