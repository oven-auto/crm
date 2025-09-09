<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class WSMRedemptionCarFilter extends AbstractFilter
{
    private const AUTHOR_ID = 'author_id';
    private const IDS = 'ids';
    private const INIT = 'init';
    private const INTERVAL = 'interval';
    private const STATUS = 'status';
    private const INPUT = 'input';
    private const WORKSHEET_ID = 'worksheet_id';
    private const EXECUTOR_ID = 'executor_id';
    private const PETITION_BEGIN = 'petition_begin';
    private const PETITION_END = 'petition_end';
    private const APPRAISAL_BEGIN = 'appraisal_begin';
    private const APPRAISAL_END = 'appraisal_end';
    private const BRAND_ID = 'brand_ids';
    private const MARK_ID = 'mark_ids';

    public function __construct(array $queryParams)
    {
        $queryParams['init'] = 'init';
        parent::__construct($queryParams);
    }



    protected function getCallbacks(): array
    {
        return [
            self::INIT               => [$this, 'init'],
            self::AUTHOR_ID          => [$this, 'authorIds'],
            self::IDS                => [$this, 'ids'],
            self::INTERVAL           => [$this, 'interval'],
            self::STATUS             => [$this, 'status'],
            self::INPUT              => [$this, 'input'],
            self::WORKSHEET_ID       => [$this, 'worksheetId'],
            self::EXECUTOR_ID        => [$this, 'executorId'],
            self::PETITION_BEGIN     => [$this, 'petitionBegin'],
            self::PETITION_END       => [$this, 'petitionEnd'],
            self::APPRAISAL_BEGIN    => [$this, 'appraisalBegin'],
            self::APPRAISAL_END      => [$this, 'appraisalEnd'],
            self::BRAND_ID           => [$this, 'brandId'],
            self::MARK_ID            => [$this, 'markId'],
        ];
    }



    public function init(Builder $builder)
    {
        $builder->leftJoin('worksheets', 'worksheets.id', 'wsm_redemption_cars.worksheet_id');
        $builder->leftJoin('wsm_redemption_calculations', 'wsm_redemption_calculations.wsm_redemption_car_id', 'wsm_redemption_cars.id');
        $builder->leftJoin('wsm_redemption_offers',     'wsm_redemption_offers.wsm_redemption_car_id', 'wsm_redemption_cars.id');
        $builder->leftJoin('wsm_redemption_purchases', 'wsm_redemption_purchases.wsm_redemption_car_id', 'wsm_redemption_cars.id');
        $builder->leftJoin('wsm_redemption_appraisals', 'wsm_redemption_appraisals.redemption_id', 'wsm_redemption_cars.id');
        $builder->leftJoin('client_cars', 'client_cars.id', 'wsm_redemption_cars.client_car_id');
        $builder->leftJoin('clients', 'clients.id', 'wsm_redemption_cars.client_id');
        $builder->leftJoin('client_phones', 'client_phones.client_id', 'clients.id');

        $builder->groupBy('wsm_redemption_cars.id');
    }



    public function brandId(Builder $builder, $value)
    {
        if (is_array($value))
            $builder->whereIn('client_cars.brand_id', $value);
        elseif (is_numeric($value))
            $builder->where('client_cars.brand_id', $value);
    }



    public function markId(Builder $builder, $value)
    {
        if (is_array($value))
            $builder->whereIn('client_cars.mark_id', $value);
        elseif (is_numeric($value))
            $builder->where('client_cars.mark_id', $value);
    }



    public function petitionBegin(Builder $builder, $value)
    {
        $date = $this->formatDate($value);
        $builder->whereDate('wsm_redemption_cars.created_at', '>=', $date);
    }



    public function petitionEnd(Builder $builder, $value)
    {
        $date = $this->formatDate($value);
        $builder->whereDate('wsm_redemption_cars.created_at', '<=', $date);
    }



    public function appraisalBegin(Builder $builder, $value)
    {
        $date = $this->formatDate($value);
        $builder->whereDate('wsm_redemption_appraisals.created_at', '>=', $date);
    }



    public function appraisalEnd(Builder $builder, $value)
    {
        $date = $this->formatDate($value);
        $builder->whereDate('wsm_redemption_appraisals.created_at', '<=', $date);
    }



    public function worksheetId(Builder $builder, $value)
    {
        $builder->where('wsm_redemption_cars.worksheet_id', $value);
    }



    public function authorIds(Builder $builder, $value)
    {
        if (is_array($value))
            $builder->whereIn('wsm_redemption_cars.author_id', $value);
        elseif (is_numeric($value))
            $builder->where('wsm_redemption_cars.author_id', $value);
    }



    public function ids(Builder $builder, $value)
    {
        if (is_array($value))
            $builder->whereIn('wsm_redemption_cars.id', $value);
        elseif (is_numeric($value))
            $builder->where('wsm_redemption_cars.id', $value);
    }



    public function interval(Builder $builder, $value)
    {
        switch ($value) {
            case 'today':
                $builder->whereDate('wsm_redemption_cars.created_at', now());
                break;
            case 'week':
                $builder->whereBetween('wsm_redemption_cars.created_at', [
                    now()->startOfWeek(), now()->endOfWeek()
                ]);
                break;
            case 'month':
                $builder->where(function ($query) {
                    $query
                        ->whereYear('wsm_redemption_cars.created_at', '=', now()->year)
                        ->whereMonth('wsm_redemption_cars.created_at', '=', now()->month);
                });
                break;
        }
    }



    public function status(Builder $builder, $value)
    {
        switch ($value) {
            case 'stock':
                $builder->where('wsm_redemption_cars.redemption_status_id', 2);
                break;
            case 'wait':
                $builder->where('wsm_redemption_cars.redemption_status_id', 1)
                    ->havingRaw(('COUNT(wsm_redemption_calculations.id) < 1'))
                    ->havingRaw(('COUNT(wsm_redemption_offers.id) < 1'))
                    ->havingRaw(('COUNT(wsm_redemption_purchases.id) < 1'));
                break;
            case 'work':
                $builder->where('wsm_redemption_cars.redemption_status_id', 1)
                    ->orHavingRaw(('COUNT(wsm_redemption_calculations.id) > 0'))
                    ->orHavingRaw(('COUNT(wsm_redemption_offers.id) > 0'))
                    ->orHavingRaw(('COUNT(wsm_redemption_purchases.id) > 0'));
                break;
            case 'close':
                $builder->where('wsm_redemption_cars.redemption_status_id', 3);
                break;
            case 'control':
                $builder->where('wsm_redemption_cars.redemption_status_id', 1)
                    ->whereIn('worksheets.status_id', ['confirm', 'check']);
                break;
        }
    }



    public function input(Builder $builder, $value)
    {
        $isCommand = strpos($value, '/');

        if ($isCommand === 0) {
            $builder->where('wsm_redemption_cars.id', 'LIKE', '%' . trim($value, '/') . '%');
            return;
        } else {
            $builder->where(function ($query) use ($value) {

                $query->orWhere('client_cars.vin', 'LIKE', '%' . $value . '%');
                $query->orWhere('client_cars.register_plate', 'LIKE', '%' . $value . '%');
                $query->orWhere('client_phones.phone', 'LIKE', '%' . $value . '%');
                $query->orWhere('clients.lastname', 'LIKE', '%' . $value . '%');
                $query->orWhere('wsm_redemption_cars.worksheet_id', 'LIKE', '%' . $value . '%');
                $query->orWhere('wsm_redemption_cars.id', 'LIKE', '%' . trim($value, '/') . '%');
            });
        }
    }



    public function executorId(Builder $builder, $value)
    {
        $builder->where('wsm_redemption_appraisals.author_id', $value);
    }
}
