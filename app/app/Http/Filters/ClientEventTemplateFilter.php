<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

Class ClientEventTemplateFilter extends AbstractFilter
{
    public const TRASH = 'trash';
    public const PROCESS = 'process';



    public function __construct(array $queryParams)
    {
        $queryParams['init'] = 'init';

        parent::__construct($queryParams);
    }



    public function getCallbacks() :array
    {
        return [
            self::TRASH => [$this, 'fnTrash'],
            self::PROCESS => [$this, 'fnProcess'],
        ];
    }



    public function init(Builder $builder)
    {

    }



    public function fnTrash(Builder $builder, bool $val)
    {
        match($val){
            true => $builder->onlyTrashed(),
            default => '',
        };
    }



    public function fnProcess(Builder $builder, int $val)
    {
        $builder->where('process_id', $val);
    }
}