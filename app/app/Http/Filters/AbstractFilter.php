<?php


namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

abstract class AbstractFilter implements FilterInterface
{
    /** @var array */
    private $queryParams = [];
    
    abstract protected function getCallbacks(): array;

    /**
     * AbstractFilter constructor.
     *
     * @param array $queryParams
     */
    public function __construct(array $queryParams)
    {
        $this->queryParams = $queryParams;
    }

    

    public function apply(Builder $builder)
    {
        $this->before($builder);

        foreach ($this->getCallbacks() as $name => $callback) {
            if (isset($this->queryParams[$name])) {
                call_user_func($callback, $builder, $this->queryParams[$name]);
            }
        }
    }

   
    
    protected function before(Builder $builder)
    {
    }

   
    
    protected function getQueryParam(string $key, $default = null)
    {
        return $this->queryParams[$key] ?? $default;
    }

   
    
    protected function removeQueryParam(string ...$keys)
    {
        foreach ($keys as $key) {
            unset($this->queryParams[$key]);
        }

        return $this;
    }



    protected function formatDate($value, $format = 'Y-m-d')
    {
        $date = new Carbon($value);
        
        return $date->format($format);
    }



    protected function checkJoin(Builder $builder, $table)
    {
        $res = collect($builder->getQuery()->joins)->pluck('table')->contains($table);
        
        return $res;
    }
}
