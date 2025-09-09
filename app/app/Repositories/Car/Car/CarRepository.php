<?php

namespace App\Repositories\Car\Car;

use App\Classes\Car\CarPriority\CarPriority;
use App\Classes\Notice\Notice;
use App\Classes\Wait\Wait;
use App\Models\Car;
use App\Http\Filters\CarFilter;
use App\Models\CarState;
use App\Models\DealerColorImage;
use App\Repositories\Car\Car\DTO\CarCountDTO;
use App\Repositories\Car\Car\DTO\CarDTO;
use App\Repositories\Car\Car\DTO\CarTuningDTO;
use App\Repositories\Car\Car\DTO\LogisticDateDTO;
use App\Services\Car\CarLogisticStateService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class CarRepository
{
    /**
     * SAVE RELATION
     */
    public function saveRelationFasade(Car $car, array $data)
    {
        //Создаем если не было создано, либо изменяем НОМЕР ЗАКАЗА
        $car->saveOrderNumber($data['order_number'] ?? '');

        //сохраняем маркер логиста, так как поле не обязательное может быть пустота
        $car->saveMarker($data['marker_id'] ?? null);

        //Сохраняем товарный признак авто
        $car->saveTradeMarker($data['trade_marker_id'] ?? null);

        //Сохраняем поставщика
        $car->saveProvider($data['provider_id'] ?? null);

        //Сохраняем тип заказа, сохранением занимается метод модели car
        $car->saveOrderType($data['order_type_id'] ?? null);

        //Сохраняем даты логистики
        $car->saveLogisticDates(new LogisticDateDTO($data ?? []));

        //Сохраняем поставщика
        $car->saveTechnic($data['technic_id'] ?? null);

        //Сохраняем аудио
        $car->saveAudio($data['audio_code'] ?? null);

        //Сохраняем закуп
        $car->savePurchase($data['purchase_cost'] ?? null);

        //Сохраняем условия поставки
        $car->saveDeliveryTerm($data['delivery_term_id'] ?? null);

        //Сохраняем опции
        $car->saveOptions($data['options'] ?? null);

        //Сохраняем детализацию цены
        $car->saveDetailingCosts($data['detailing_costs'] ?? null);

        //Сохраняем тюнинг
        $car->saveTuning(($data['devices'] ?? []));

        //Save comment
        $car->saveComment($data['comment'] ?? '');

        //Save collector
        $car->saveCollector($data['collector_id'] ?? null);

        //Save Owner
        $car->saveOwner($data['owner'] ?? null);

        //save gift price
        $car->saveGiftPrice($data['gift_price'] ?? null);

        //save part price
        $car->savePartPrice($data['part_price'] ?? null);

        //save tuning price
        $car->saveTuningPrice($data['tuning_price'] ?? null);
        
        $car->savePaidDate($data['paid_date'] ?? null);

        $car->saveControlPaidDate($data['control_paid_date'] ?? null);
    }



    /**
     * SAVE OVER PRICE
     */
    public function saveOverPrice(Car $car, int $price)
    {
        $car->saveOverPrice($price);
    }



    public function setCarStatus(Car $car)
    {   
        $stateService = new CarLogisticStateService($car);

        $lastState = $stateService->getLastLogisticState();

        if (!$lastState)
            return;

        $carState = CarState::query()->where('logistic_system_name', $lastState->logistic_system_name)->first();

        $car->saveCarStatus($carState);
    }



    public function setCarImage(Car $car)
    {
        if($car->image->count())
            return;

        $bodyWork = $car->complectation->body_work_id;

        $colorImage = $car->color->images->where('body_work_id', $bodyWork)->first();

        if($colorImage)
        {
            $car->image()->sync([$colorImage->id]);
            $car->load('image');
        }
    }



    /**
     * CREATE
     */
    public function store(array $data)
    {
        try {
            $result = DB::transaction(function () use ($data) {
                $car = Car::create(array_merge((new CarDTO($data))->get(), ['author_id' => auth()->user()->id]));

                $data['application_date'] = $car->created_at->format('d.m.Y');

                $this->saveRelationFasade($car, $data);

                $car->load('logistic_dates');

                $this->setCarStatus($car);

                $car->refresh();

                $this->setCarImage($car);

                Notice::setMessage('Автомобиль добавлен.');

                return $car;
            }, 3);
        } catch (\Exception $exception) {
            throw new \Exception('Произошла ошибка, автомобиль не добавлен.');
        }

        return $result;
    }



    /**
     * UPDATE
     */
    public function update(Car $car, array $data)
    {
        try {
            DB::transaction(function () use ($car, $data) {
                $data['application_date'] = $car->created_at->format('d.m.Y');

                $car->fill((new CarDTO($data))->get())->save();

                $this->saveRelationFasade($car, $data);

                $car->load('logistic_dates');

                $this->setCarStatus($car);

                $car->refresh();

                $this->setCarImage($car);

                CarPriority::make($car)->checkPriority();

                Notice::setMessage('Автомобиль изменен.');
            }, 3);
        } catch (\Exception $exception) {
            throw new \Exception('Произошла ошибка, автомобиль не изменен. '.$exception->getMessage());
        }
    }



    /**
     * PAGINATE
     */
    public function get(array $data = [], $limit = 1000)
    {   
        $query = Car::query()->select('cars.*');
        
        $filter = app()->make(CarFilter::class, ['queryParams' => $data]);
        
        $query->withDataForCarList();

        $query->filter($filter);
        
        $query->orderBy('cars.brand_id',    'ASC');
        
        $query->orderBy('cars.mark_id',     'ASC');
       
        $cars = $query->limit($limit)->get();
        
        $cars->each(function($item) {
            if(isset($item->reserve))
                $item->reserve->car = $item;
        });
        
        return $cars;
    }



    /**
     * PAGINATE
     */
    public function paginate(array $data = [], $paginate = 25)
    {   
        $query = Car::query()->select('cars.*');
        
        $filter = app()->make(CarFilter::class, ['queryParams' => $data]);
        
        $query->filter($filter);

        $query->withDataForCarList();
   
        $cars = $query->simplePaginate($paginate);
        
        $cars->each(function($item) {
            if(isset($item->reserve))
                $item->reserve->car = $item;
        });
        
        return $cars;
    }



    /**
     * COUNT CARS WITH FILTER
     * @param array $data FILTER DATA
     * @return int $countCars
     */
    public function count(array $data = [])
    {
        $query = Car::query()
            ->select(
                'cars.id', 
                'cfp.tuningprice as t_price', 
                'cfp.overprice as ov_price',
                'cfp.giftprice as gift_price',
                DB::raw('IF(cp.price IS NOT NULL, cp.price, cfp.complectationprice) as com_price'),
                DB::raw('IF(joinOptionPrice.sum_option IS NOT NULL, joinOptionPrice.sum_option, cfp.optionprice) as op_price'),
                DB::raw('(_joinds._dsum) as discount_price'),
                
                DB::raw('IF(car_collectors.id IS NOT NULL, 1, 0) as collector'),
                
                DB::raw('(_joinds._repsum) as full_reparation'),

                'car_owners.id as owner_count',

                DB::raw('IF(cars.disable_off, cars.disable_off, 0) as _disable'),
                
                DB::raw('IF(
                        (
                            car_owners.client_id = wsm_reserve_lisings.client_id OR
                            car_owners.client_id = worksheets.client_id
                        ) and  car_owners.id IS NOT NULL, 1, 0
                ) as green_report'),
                
                DB::raw('IF(
                    car_owners.client_id <> IFNULL(worksheets.client_id, 0) and
                    car_owners.client_id <> IFNULL(wsm_reserve_lisings.client_id, 0) and
                    car_owners.id IS NOT NULL, 1, 0
                ) as yellow_report'),
                
                DB::raw('IF(ransom_cars.car_id, 1, 0) as ransom_date'),
                DB::raw('IF(ransom_cars.car_id, _purchase.cost, 0) as ransom_sum'),
                DB::raw('IF(ransom_cars.car_id, sum(car_detailing_costs.price), 0) as ransom_detailing'),
                DB::raw('IF(ransom_cars.car_id, IF(car_collectors.id IS NOT NULL, 1, 0), 0) as ransom_collector'),
                DB::raw('IF(ransom_cars.car_id, _joinds._repsum, 0) as ransom_reparation'),
               
                DB::raw('IF(_purchase.id, 1, 0) as factoring_count'),
                DB::raw('IF(_purchase.id, _purchase.cost, 0) as factoring_sum'),
                DB::raw('IF(_purchase.id, sum(car_detailing_costs.price), 0) as factoring_detailing'),
                DB::raw('IF(_purchase.id, IF(car_collectors.id IS NOT NULL, 1, 0), 0) as factoring_collector'),
                DB::raw('IF(_purchase.id, _joinds._repsum, 0) as factoring_reparation'),
            );

        $filter = app()->make(CarFilter::class, ['queryParams' => ($data)]);

        $query->filter($filter);

        $query->leftJoin(DB::raw('(
                SELECT 
                    sum(_ds.amount) as _dsum, 
                    sum(_dr.amount) as _repsum, 
                    _d.modulable_id as _dreserve 
                FROM discounts as _d 
                LEFT JOIN discount_sums as _ds on _ds.discount_id = _d.id
                LEFT JOIN discount_reparations as _dr on _dr.discount_id = _d.id 
                WHERE _d.modulable_type = "App\\\\Models\\\\WsmReserveNewCar"
                group by _d.modulable_id
            ) as _joinds'), '_joinds._dreserve', 'reserve.id');

        $query->leftJoin('car_purchases as _purchase', '_purchase.car_id', 'cars.id');
        
        $countCars = DB::table($query)->select(
            DB::raw('CAST(count(id)                 as SIGNED) as count'),
            DB::raw('CAST(sum(t_price)              as SIGNED) as tuning'),
            DB::raw('CAST(sum(ov_price)             as SIGNED) as overprice'),
            DB::raw('CAST(sum(com_price)            as SIGNED) as base'),
            DB::raw('CAST(sum(op_price)             as SIGNED) as option'),
            DB::raw('CAST(sum(discount_price)       as SIGNED) as discount'),
            DB::raw('CAST(sum(collector)            as SIGNED) as collector'),
            DB::raw('CAST(sum(gift_price)           as SIGNED) as giftprice'),
            DB::raw('CAST(sum(full_reparation)      as SIGNED) as full_reparation'),

            DB::raw('CAST(sum(_disable)             as SIGNED) as disable'),
            DB::raw('CAST(count(owner_count)        as SIGNED) as owner'),
            DB::raw('CAST(sum(green_report)         as SIGNED) as green'),
            DB::raw('CAST(sum(yellow_report)        as SIGNED) as yellow'),

            DB::raw('CAST(sum(factoring_sum)        as SIGNED) as factoring_sum'),
            DB::raw('CAST(sum(factoring_count)      as SIGNED) as factoring_count'),
            DB::raw('CAST(sum(factoring_detailing)  as SIGNED) as factoring_detailing'),
            DB::raw('CAST(sum(factoring_collector)  as SIGNED) as factoring_collector'),
            DB::raw('CAST(sum(factoring_reparation) as SIGNED) as factoring_reparation'),

            DB::raw('CAST(sum(ransom_date)          as SIGNED) as ransom_count'),
            DB::raw('CAST(sum(ransom_sum)           as SIGNED) as ransom_sum'),
            DB::raw('CAST(sum(ransom_detailing)     as SIGNED) as ransom_detailing'),
            DB::raw('CAST(sum(ransom_collector)     as SIGNED) as ransom_collector'),
            DB::raw('CAST(sum(ransom_reparation)    as SIGNED) as ransom_reparation'),
        )->first();
        
        return $countCars;
    }



    public function countSum(array $data = [])
    {
        $query = Car::query()
            ->select(
                'cars.id', 
                'cfp.tuningprice as t_price', 
                'cfp.overprice as ov_price',
                'cfp.giftprice as gift_price',
                DB::raw('IF(cp.price IS NOT NULL, cp.price, cfp.complectationprice) as com_price'),
                DB::raw('IF(joinOptionPrice.sum_option IS NOT NULL, joinOptionPrice.sum_option, cfp.optionprice) as op_price'),
                DB::raw('sum(_disc_sum.amount) as discount_price'),
            );  

        $filter = app()->make(CarFilter::class, ['queryParams' => ($data)]);

        $query->filter($filter);

        $query->leftJoin('discount_sums as _disc_sum', '_disc_sum.discount_id', 'discounts.id');

        $countSum = DB::table($query)->select(
            DB::raw('count(id)              as count'),
            DB::raw('sum(t_price)           as tuning'),
            DB::raw('sum(ov_price)          as overprice'),
            DB::raw('sum(com_price)         as base'),
            DB::raw('sum(op_price)          as option'),
            DB::raw('sum(discount_price)    as discount'),
            DB::raw('sum(gift_price)        as giftprice'),
        )->first();

        return $countSum;
    }



    /**
     * ОТЧЕТ
     */
    public function countReport(array $data = [])
    {
        $query = Car::query()
            ->select(
                'cars.id', 
                'car_owners.id as owner_count',
                DB::raw('IF(cars.disable_off, cars.disable_off, 0) as _disable'),
                DB::raw('IF(car_owners.client_id = IF(worksheets.client_id IS NULL, 0, worksheets.client_id) and car_owners.id IS NOT NULL, 1, 0) as green_report'),
                DB::raw('IF(car_owners.client_id <> IF(worksheets.client_id IS NULL, 0, worksheets.client_id)  and car_owners.id IS NOT NULL, 1, 0) as yellow_report'),
            );

        $filter = app()->make(CarFilter::class, ['queryParams' => ($data)]);

        $query->filter($filter);

        $countReport = DB::table($query)->select(
            DB::raw('sum(_disable)          as disable'),
            DB::raw('count(owner_count)     as owner'),
            DB::raw('sum(green_report)      as green'),
            DB::raw('sum(yellow_report)     as yellow'),
        )->first();

        return $countReport;
    }



    /**
     * ВЫКУПНОЙ
     */
    public function countRansom(array $data = [])
    {
        $query = Car::query()
            ->select(
                'cars.id', 
                DB::raw('IF(ransom_cars.car_id, 1, 0) as ransom_date'),
                DB::raw('IF(ransom_cars.car_id, _purchase.cost, 0) as ransom_sum'),
                DB::raw('IF(ransom_cars.car_id, sum(car_detailing_costs.price), 0) as ransom_detailing'),
                DB::raw('IF(ransom_cars.car_id, IF(car_collectors.id IS NOT NULL, 1, 0), 0) as ransom_collector')
            )
            //->leftJoin('ransom_cars as _ransom_car', '_ransom_car.car_id', 'cars.id')
            //->leftJoin('car_collectors as _collector', '_collector.car_id', 'cars.id')
            ->leftJoin('car_purchases as _purchase', '_purchase.car_id', 'cars.id');
            //->leftJoin('car_detailing_costs as _cd_cost', '_cd_cost.car_id', 'cars.id');

        $filter = app()->make(CarFilter::class, ['queryParams' => ($data)]);

        $query->filter($filter);

        $countRansom = DB::table($query)->select(
            DB::raw('sum(ransom_date)       as ransom_count'),
            DB::raw('sum(ransom_sum)        as ransom_sum'),
            DB::raw('sum(ransom_detailing)  as ransom_detailing'),
            DB::raw('sum(ransom_collector)  as ransom_collector'),
        )->first();

        return $countRansom;
    }



    /**
     * ФАКТУРНЫЙ
     */
    public function countFactoring(array $data = [])
    {
        $query = Car::query()
            ->select(
                'cars.id', 
                DB::raw('IF(_purchase.id, 1, 0) as factoring_count'),
                DB::raw('IF(_purchase.id, _purchase.cost, 0) as factoring_sum'),
                DB::raw('IF(_purchase.id, sum(car_detailing_costs.price), 0) as factoring_detailing'),
                DB::raw('IF(_purchase.id, IF(car_collectors.id IS NOT NULL, 1, 0), 0) as factoring_collector'),
            )
            //->leftJoin('car_collectors as _collector', '_collector.car_id', 'cars.id')
            ->leftJoin('car_purchases as _purchase', '_purchase.car_id', 'cars.id');
            //->leftJoin('car_detailing_costs as _cd_cost', '_cd_cost.car_id', 'cars.id');

        $filter = app()->make(CarFilter::class, ['queryParams' => ($data)]);

        $query->filter($filter);

        $countRansom = DB::table($query)->select(
            DB::raw('sum(factoring_sum)         as factoring_sum'),
            DB::raw('sum(factoring_count)       as factoring_count'),
            DB::raw('sum(factoring_detailing)   as factoring_detailing'),
            DB::raw('sum(factoring_collector)   as factoring_collector'),
        )->first();

        return $countRansom;
    }



    public function clone(Car $car)
    {
        $clone = new Car();
        
        $clone->fill([
            'year'                      => $car->year,
            'mark_id'                   => $car->mark_id, //model
            'brand_id'                  => $car->brand_id, //brand
            'complectation_id'          => $car->complectation_id,
            'author_id'                 => $car->author_id,
            'status'                    => $car->status,
            'created_at'                => $car->created_at,
        ]);
        
        $clone->trade_marker            = $car->trade_marker;
        $clone->provider                = $car->provider;

        return $clone;
    }
}
