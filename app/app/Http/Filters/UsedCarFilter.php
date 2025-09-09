<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class UsedCarFilter extends AbstractFilter
{
    public const WORKSHEET_ID = 'worksheet_id';
    public const BRAND_ID = 'brand_id';
    public const MARK_ID = 'mark_id';
    public const AGENT_ID = 'agent_id';
    public const BODYWORK_ID = 'bodywork_id';
    public const COLOR_ID = 'color_id';
    public const AUTHOR_ID = 'author_id';
    public const VEHICLE_ID = 'vehicle_id';

    public const INIT = 'init';



    public function __construct(array $queryParams)
    {
        $queryParams['init'] = 'init';
        parent::__construct($queryParams);
    }



    protected function getCallbacks(): array
    {
        return [
            self::INIT => [$this, 'init'],
            self::WORKSHEET_ID => [$this, 'worksheetId'],
        ];
    }



    public function init(Builder $builder)
    {
        $builder->leftJoin('wsm_redemption_cars', 'wsm_redemption_cars.id', 'used_cars.wsm_redemption_car_id');
    }



    public function worksheetId(Builder $builder, string $value)
    {
        $builder->where('wsm_redemption_cars.worksheet_id', $value);
    }
}
