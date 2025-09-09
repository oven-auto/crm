<?php

namespace App\Repositories\Services\DTO;

use Illuminate\Support\Arr;

Class ServiceDTO
{
    public function __construct(
        public readonly int $category_id,
        public readonly string $name,
        public readonly array $providers,
        //public readonly int $cost,
        public readonly int $company_award,
        public readonly int $design_award,
        public readonly int $sale_award,
        public readonly array $applicability,
        public readonly int $reminder,
        public readonly int $manager_id,
        public readonly bool $prolongation,
        public readonly int|null $template_id, 
    )
    {
        
    }



    public static function fromArray(array $data) : self
    {   
        return new self(
            category_id     : $data['category'],
            name            : $data['name'],
            providers       : $data['providers'] ?? [],
            company_award   : $data['company_award'] ?? 0,
            design_award    : $data['design_award'] ?? 0,
            sale_award      : $data['sale_award'] ?? 0,
            applicability   : $data['applicability'],
            reminder        : $data['duration'] ?? 0,
            manager_id      : $data['manager'] ?? 0,
            prolongation    : $data['prolongation'] ?? 0,
            template_id     : (isset($data['template']) && $data['template'] > 0) ? $data['template'] : null,
        );
    }



    public function mainData()
    {
        $returned = Arr::only((array)$this, ['category_id', 'name', 'provider_id',]);

        return $returned;
    }



    public function calculationData()
    {
        $returned = Arr::only((array)$this, ['cost', 'company_award', 'design_award', 'sale_award']);
       
        return $returned;
    }



    public function prolongationData()
    {
        return Arr::only((array) $this, ['prolongation', 'template_id',]);
    }
}