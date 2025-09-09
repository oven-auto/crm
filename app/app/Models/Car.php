<?php

namespace App\Models;

use App\Helpers\Number\NumberHelper;
use App\Helpers\String\StringHelper;
use App\Models\Interfaces\CarableInterface;
use App\Models\Traits\CarPaginatable;
use App\Repositories\Car\Car\DTO\LogisticDateDTO;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Filterable;
use App\Models\Worksheet\Service\WSMServiceCar;
use App\Services\Car\CarLogisticStateService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;

/**
 * CAR MODEL
 *
 * @method option_price         relation hasOne
 * @method author               relation hasOne
 * @method brand                relation hasOne
 * @method mark                 relation hasOne
 * @method complectation        relation hasOne
 * @method color                relation hasOne
 * @method order                relation hasOne
 * @method provider             relation hasOne
 * @method marker               relation hasOne
 * @method trade_marker         relation hasOne
 * @method order_type           relation hasOne
 * @method logistic_dates       relation hasMany
 * @method technic              relation hasOne
 * @method audio                relation hasOne
 * @method purchase             relation hasOne
 * @method delivery_terms       relation hasOne
 * @method options              relation belongsToMany
 * @method detailing_costs      relation hasMany
 * @method tuning               relation belongsToMany
 * @method tuning_price         relation hasOne
 * @method over_price           relation hasOne
 * @method full_price           relation hasOne
 * @method state_status         relation hasOne
 * @method comment              relation hasOne
 * @method collector            relation hasOne
 * @method reserve              relation hasOne
 * @method owner                relation hasOne
 *
 * SAVE
 *
 * @method saveOrderType        @param int|null $orderTypeId
 * @method saveOrderNumber      @param string|null $orderNumber
 * @method saveMarker           @param int|null $markerId
 * @method saveTradeMarker      @param int|null $tradeMarkerId
 * @method saveProvider         @param int|null $providerId
 * @method saveLogisticDates    @param LogisticDateDTO $dto
 * @method saveTechnic          @param int|null $technicId
 * @method saveAudio            @param string|null $audioCode
 * @method savePurchase         @param int|null $cost
 * @method saveDeliveryTerm     @param array|null $deliveryTerms
 * @method saveOptions          @param array|null $optionArray
 * @method saveDetailingCost    @param array|null $detailingArray
 * @method saveTuning           @param CatTuningDTO|null $tuningArray
 * @method saveOverPrice        @param int|null $overPrice
 * @method saveComment          @param string $comment
 * @method saveCollector        @param int $collector_id
 *
 * ATTRIBUTES
 *
 * @method getStatusAttribute
 *
 * GET
 *
 * @method getLogisticDates     @param string|null $key @param string $format
 * @method getLogisticAuthors   @param string|null $key @param srring $format
 */



class Car extends Model implements CarableInterface
{
    use HasFactory, SoftDeletes, Filterable, CarPaginatable;

    protected $guarded = [];

    public const REPORT_STATUSES = ['1' => 'Зеленый рапорт', '2' => 'Жёлтый рапорт'];

    /**
     * SCOPES
     */


    /* RELATIONS */



    public function service(): MorphOne
    {
        return $this->morphOne(WSMServiceCar::class, 'carable');
    }



    public function priority()
    {
        return $this->hasOne(\App\Models\CarSalePriority::class, 'car_id', 'id')->withDefault();
    }



    public function paid_date()
    {
        return $this->hasOne(\App\Models\CarPaidDate::class, 'car_id', 'id');
    }



    public function control_paid_date()
    {
        return $this->hasOne(\App\Models\CarControllPaidDate::class, 'car_id', 'id');
    }



    public function history()
    {
        return $this->hasMany(\App\Models\CarTuningHistory::class, 'car_id', 'id')->orderBy('created_at', 'DESC');
    }



    public function car_status_type()
    {
        return $this->hasOne(\App\Models\CarStatusType::class, 'car_id', 'id')->withDefault();
    }



    public function state_status()
    {
        return $this->hasOne(\App\Models\CarState::class, 'status', 'status');
    }

    /**
     * OWNER
     */
    public function owner()
    {
        return $this->hasOne(\App\Models\CarOwner::class, 'car_id', 'id');
    }



    /**
     * TUNING
     */
    public function tuning()
    {
        return $this->belongsToMany(\App\Models\Tuning::class, 'car_tunings', 'car_id')
            ->using(CarTuning::class)
            ->withTrashed();
    }



    /**
     * TUNING PRICE
     */
    public function tuning_price()
    {
        return $this->hasOne(\App\Models\CarTuningPrice::class, 'car_id', 'id');
    }



    /**
     * GIFT PRICE
     */
    public function gift_price()
    {
        return $this->hasOne(\App\Models\CarGiftPrice::class, 'car_id', 'id');
    }



    /**
     * PART PRICE
     */
    public function part_price()
    {
        return $this->hasOne(\App\Models\CarPartPrice::class, 'car_id', 'id');
    }



    /**
     * OVER PRICE
     */
    public function over_price()
    {
        return $this->hasOne(\App\Models\CarOverPrice::class, 'car_id', 'id')->withDefault();
    }



    /**
     *
     */
    // public function option_price()
    // {
    //     return $this->hasOne(\App\Models\CarOptionPrice::class, 'car_id', 'id')->withDefault();
    // }
    public function image()
    {
        return $this->belongsToMany(\App\Models\DealerColorImage::class, 'car_images', 'car_id', 'image_id');
    }



    /**
     * AUTHOR
     */
    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }



    /**
     * BRAND
     */
    public function brand()
    {
        return $this->hasOne(\App\Models\Brand::class, 'id', 'brand_id');
    }



    /**
     * MODEL
     */
    public function mark()
    {
        return $this->hasOne(\App\Models\Mark::class, 'id', 'mark_id');
    }



    /**
     * COMPLECTATION
     */
    public function complectation()
    {
        return $this->hasOne(\App\Models\Complectation::class, 'id', 'complectation_id')->withTrashed();
    }



    /**
     * COLOR
     */
    public function color()
    {
        return $this->hasOne(\App\Models\DealerColor::class, 'id', 'color_id');
    }



    /**
     * ORDER NUMBER
     */
    public function order()
    {
        return $this->hasOne(\App\Models\CarOrder::class, 'car_id', 'id');
    }



    /**
     * PROVIDER
     */
    public function provider()
    {
        return $this->hasOne(\App\Models\CarProvider::class, 'car_id', 'id');
    }



    /**
     * MARKER
     */
    public function marker()
    {
        return $this->hasOne(\App\Models\CarMarker::class, 'car_id', 'id')->withDefault();
    }



    /**
     * TRADE MARKER
     */
    public function trade_marker()
    {
        return $this->hasOne(\App\Models\CarTradeMarker::class, 'car_id', 'id');
    }



    /**
     * ORDER_TYPE
     */
    public function order_type()
    {
        return $this->hasOne(\App\Models\CarOrderType::class, 'car_id', 'id');
    }



    /**
     * LOGISTIC DATES
     */
    public function logistic_dates()
    {
        return $this->hasMany(\App\Models\CarDateLogistic::class, 'car_id', 'id');
    }



    /**
     * TECHNIC
     */
    public function technic()
    {
        return $this->hasOne(\App\Models\CarTechnic::class, 'car_id', 'id');
    }



    /**
     * AUDIO
     */
    public function audio()
    {
        return $this->hasOne(\App\Models\CarAudio::class, 'car_id', 'id');
    }



    /**
     * PURCHASE
     */
    public function purchase()
    {
        return $this->hasOne(\App\Models\CarPurchase::class, 'car_id', 'id')->withDefault();
    }



    /**
     * CAR DELIVERY TERM
     */
    public function delivery_terms()
    {
        return $this->hasOne(\App\Models\CarDeliveryTerm::class, 'car_id', 'id')->withDefault();
    }



    /**
     * CAR OPTIONS
     */
    public function options()
    {
        return $this->belongsToMany(\App\Models\Option::class, 'car_options', 'car_id');
    }



    /**
     * CAR DETAILING COST
     */
    public function detailing_costs()
    {
        return $this->hasMany(\App\Models\CarDetailingCost::class, 'car_id', 'id');
    }



    /**
     * COMMENT
     */
    public function comment()
    {
        return $this->hasOne(\App\Models\CarComment::class, 'car_id', 'id');
    }



    /**
     * COLLECTOR
     */
    public function collector()
    {
        return $this->hasOne(\App\Models\CarCollector::class, 'car_id', 'id');
    }



    /**
     * RESERVE
     */
    public function reserve()
    {
        return $this->hasOne(\App\Models\WsmReserveNewCar::class, 'car_id', 'id');
    }



    /*************************************************** */
    /*************************************************** */
    /**ATTRIBUTES*************************************** */
    /*************************************************** */
    /*************************************************** */



    /**
     * CAR STATUS
     */
    public function getStatusAllAttribute()
    {
        $state = $this->logistic_dates->where('state.state', $this->logistic_dates->max('state.state'))->first();

        $state_status = $this->state_status;

        if ($state_status)
            return join(' ', [
                $state_status->description ?? 'Заявка',
                $state ? $state->updated_at->format('d.m.Y') : '',
                $state ? '(' . $state->author->cut_name . ')' : ''
            ]);
    }



    /**
     * CAR STATUS WITHOUT AUTHOR
     */
    public function getStatusWithoutAuthorAttribute()
    {
        $state = $this->logistic_dates->where('state.state', $this->logistic_dates->max('state.state'))->first();

        $state_status = $this->state_status;

        return join(' ', [
            $state_status->description ?? 'Заявка',
            isset($state->updated_at) ? $state->updated_at->format('d.m.Y') : $this->created_at->format('d.m.Y (H:i)'),
        ]);
    }






    /*************************************************** */
    /*************************************************** */
    /**SAVE METHODS **************************************/
    /*****************************************************/
    /*****************************************************/



    /**
     * Сохранить начало платного периода
     */
    public function savePaidDate(string|Carbon|null $date)
    {   
        if(is_null($date))
        {
            $this->paid_date()->delete();
            return;
        }
        
        if(is_string($date))
            $date = Carbon::createFromFormat('d.m.Y', $date);
        
        if(!$this->paid_date)
            $this->paid_date()->create(['date_at' => $date, 'author_id' => Auth::id()]);
        
        elseif($date->diffInDays($this->paid_date->date_at))
            $this->paid_date()->updateOrCreate(
                ['car_id' => $this->id],
                ['date_at' => $date, 'author_id' => Auth::id()],  
            );
    }



    /**
     * Сохранить контроль срока оплаты
     */
    public function saveControlPaidDate(string|Carbon|null $date)
    {   
        if(is_null($date))
        {
            $this->control_paid_date()->delete();
            return;
        }

        if(is_string($date))
            $date = Carbon::createFromFormat('d.m.Y', $date);
        
        if(!$this->control_paid_date)
            $this->control_paid_date()->create(['date_at' => $date, 'author_id' => Auth::id()]);

        elseif($date->diffInDays($this->control_paid_date->date_at))
            $this->control_paid_date()->updateOrCreate(
                ['car_id' => $this->id],
                ['date_at' => $date, 'author_id' => Auth::id()],  
            );
    }



    /**
     * сохранить Держатель залога
     */
    public function saveCollector(int|null $collector_id = null)
    {
        if(!$collector_id)
        {
            $this->collector()->delete();
            return;
        }

        $this->collector()->updateOrCreate(
            ['car_id' => $this->id],
            [
                'collector_id' => $collector_id,
                'author_id' => auth()->user()->id
            ]
        );
    }



    /**
     * SAVE ORDER TYPE
     */
    public function saveOrderType(int|null $orderTypeId = null)
    {
        if(!$orderTypeId)
        {
            $this->order_type()->delete();
            return;
        }

        if(!$this->order_type || ($this->order_type && $this->order_type->order_type_id != $orderTypeId))
            $this->order_type()->updateOrCreate(
                ['car_id'           => $this->id],
                [
                    'order_type_id'     => $orderTypeId,
                    'author_id'         => auth()->user()->id
                ],
            );
    }



    /**
     * SAVE ORDER NUMBER
     */
    public function saveOrderNumber(string $orderNumber = null)
    {
        $this->order()->updateOrCreate(
            ['car_id'       => $this->id],
            ['order_number' => $orderNumber]
        );
    }



    /**
     * SAVE MARKER
     */
    public function saveMarker(int $markerId = null)
    {
        $this->marker()->updateOrCreate(['car_id' => $this->id], ['marker_id' => $markerId]);
    }



    /**
     * SAVE TRADE MARKER
     */
    public function saveTradeMarker(int $tradeMarkerId = null)
    {
        $this->trade_marker()->updateOrCreate(['car_id' => $this->id], ['trade_marker_id' => $tradeMarkerId]);
    }



    /**
     * SAVE PROVIDER
     */
    public function saveProvider(int $providerId = null)
    {
        $this->provider()->updateOrCreate(['car_id' => $this->id], ['provider_id' => $providerId]);
    }



    /**
     * SAVE LOGISTIC DATES
     */
    public function saveLogisticDates(LogisticDateDTO $dto)
    {

        $updateOrCreate = function ($key, $date) {
            $dateTime = new DateTime();

            $this->logistic_dates()->updateOrCreate(
                ['car_id' => $this->id, 'logistic_system_name' => $key,],
                [
                    'author_id' => auth()->user()->id,
                    'date_at' => $dateTime->createFromFormat('d.m.Y', $date),
                    'logistic_system_name' => $key,
                ]
            );
        };

        $dataFromClient = $dto->get();

        $carDates = ($this->logistic_dates);

        foreach ($carDates as $item)
            if (!array_key_exists($item->logistic_system_name, $dataFromClient))
                $item->delete();

        foreach ($dataFromClient as $key => $item)
            if (!$carDates->contains('logistic_system_name', $key))
                $updateOrCreate($key, $item);
            elseif ($carDates->where('logistic_system_name', $key)->first()->date_at->format('d.m.Y') != $item)
                $updateOrCreate($key, $item);
    }



    /**
     * SAVE TECHNIC
     */
    public function saveTechnic(int $technicId = null)
    {
        $this->technic()->updateOrCreate(['car_id' => $this->id], ['technic_id' => $technicId]);
    }



    /**
     * SAVE AUDIO
     */
    public function saveAudio(string $audioCode = null)
    {
        $audioCode == null ? $audioCode = '' : $audioCode;

        $this->audio()->updateOrCreate(['car_id' => $this->id], ['audio_code' => $audioCode]);
    }



    /**
     * SAVE PURCHASE
     */
    public function savePurchase(int $cost = null)
    {
        if(!$cost)
        {
            $this->purchase()->delete();
            return;
        }

        if($this->purchase->cost != $cost) 
            $this->purchase()->updateOrCreate([
                'car_id' => $this->id
            ], [
                'cost' => $cost ?? 0,
                'author_id' => auth()->user()->id
            ]);
    }



    /**
     * SAVE DELIVERY TERM
     */
    public function saveDeliveryTerm(int $delivery_term_id = null)
    {
        if(!$delivery_term_id)
        {
            $this->delivery_terms()->delete();
            return;
        }
                
        if($delivery_term_id != $this->delivery_terms->delivery_term_id)
            $this->delivery_terms()->updateOrCreate(
                ['car_id' => $this->id,],
                [
                    'delivery_term_id'  => $delivery_term_id,
                    'author_id'         => auth()->user()->id,
                ],
            );
    }



    /**
     * SAVE OPTIONS
     */
    public function saveOptions(array $optionArray = null)
    {
        $this->options()->sync($optionArray);
    }



    /**
     * SAVE DETAILING COST
     */
    public function saveDetailingCosts(array $detailingArray = null)
    {
        if (!$detailingArray)
            $this->detailing_costs()->delete();

        if ($detailingArray) {
            $this->detailing_costs()->delete();

            foreach ($detailingArray as $item) {
                if ($item['detailing_cost_id'])
                    $this->detailing_costs()->create(
                        [
                            'detailing_cost_id' => $item['detailing_cost_id'],
                            'price'             => $item['price'] ?? 0,
                            'coefficient'       => $item['coefficient'] ?? 1,
                        ],
                    );
            };
        }
    }



    /**
     * SAVE TUNING
     */
    public function saveTuning($tuningArray = null)
    {   
        $this->tuning()->sync($tuningArray);
    }



    /**
     * SAVE TUNING PRICE
     * @param int|null $price
     */
    public function saveTuningPrice(float|null $price) : void
    {   
        if(is_null($price))
        {
            if($this->tuning_price)
                $this->tuning_price->delete();
        }
        else
        {
            if(is_null($this->tuning_price) || $price != $this->tuning_price->price)
                $this->tuning_price()->updateOrCreate(
                    ['car_id' => $this->id],
                    ['price' => $price ?? 0, 'author_id' => auth()->user()->id],
                );
        }
    }



    /**
     * SAVE GIFT PRICE
     * @param int|null $price
     */
    public function saveGiftPrice(float|null $price) : void
    {   
        if(is_null($price))
        {
            if($this->gift_price)
                $this->gift_price->delete();
        }
        else
        {
            if(is_null($this->gift_price) || $price != $this->gift_price->price)
                $this->gift_price()->updateOrCreate(
                    ['car_id' => $this->id],
                    ['price' => $price ?? 0, 'author_id' => auth()->user()->id],
                );
        }
    }



    /**
     * SAVE PART PRICE
     * @param int|null $price
     */
    public function savePartPrice(float|null $price) : void
    {
        if(is_null($price))
        {
            if($this->part_price)
                $this->part_price->delete();
        }
        else
        {
            if(is_null($this->part_price) || $price != $this->part_price->price)
                $this->part_price()->updateOrCreate(
                    ['car_id' => $this->id],
                    ['price' => $price ?? 0, 'author_id' => auth()->user()->id],
                );
        }
    }



    /**
     * SAVE OVER PRICE
     */
    public function saveOverPrice(int|null $overPrice = null)
    {
        $currentPrice = $this->over_price->price ?? null;

        if ($overPrice !== null && $overPrice != $currentPrice)
            $this->over_price()->updateOrCreate(['car_id' => $this->id], [
                'price' => $overPrice,
                'author_id' => auth()->user()->id,
            ]);
    }



    /**
     * SAVE COMMENT
     */
    public function saveComment(string $comment = null)
    {
        $this->comment()->updateOrCreate(['car_id' => $this->id], [
            'author_id' => auth()->user()->id,
            'comment' => $comment ?? '',
        ]);
    }



    /**
     * Сохранить владельца, того на кого списывается автомобиль
     */
    public function saveOwner(int|\App\Models\Client|null $client = null)
    {
        if (!$client)
        {
            $this->owner()->delete();
            return;
        }

        if ($client instanceof Client)
            $client = $client->id;

        $this->owner()->updateOrCreate(
            ['car_id' => $this->id],
            ['client_id' => $client, 'author_id' => auth()->user()->id]
        );
    }



    /**
     * Сохранить логистический статус машины, который зависит от текущего ллогистического шага
     */
    public function saveCarStatus(CarState|null $carState)
    {
        $val = $carState ? $carState->status : null;

        $this->status = $val;

        $this->save();
    }



    /**********************************************
    /******************************************* */
    /***GET METHOD////////////////////////////// */
    /******************************************* */
    /******************************************* */



    /**
     * GET LOGISTIC DATES
     */
    public function getLogisticDates(string|null $key = null, string $format = 'd.m.Y')
    {
        if ($key) {
            $date = $this->logistic_dates->where('logistic_system_name', $key)->first();

            if ($date)
                return $date->date_at->format($format);

            return '';
        }

        return $this->logistic_dates->map(function ($item) use ($format) {
            return [$item->logistic_system_name => $item->date_at->format($format)];
        });
    }



    public function getLogisticDateByKey($key)
    {
        $date = $this->logistic_dates->where('logistic_system_name', $key)->first();
        
        if(!$date)
            return null;

        return $date->date_at->format('d.m.Y');
    }



    /**
     * GET LOGISTIC DATE AUTHORS WITH CREATE DATE
     */
    public function getLogisticAuthors(string $key = null, $format = 'd.m.Y (H:i)')
    {
        if ($key) {
            $date = $this->logistic_dates->where('logistic_system_name', $key)->first();

            if ($date)
                return [
                    'author' => $date->author->cut_name,
                    'updated_at' => $date->updated_at->format($format),
                    'date_at' => $date->date_at->format('d.m.Y'),
                ];

            return [];
        }
        return $this->logistic_dates->map(function ($item) use ($format) {
            return [$item->logistic_system_name => [
                'author'        => $item->author->cut_name,
                'updated_at'    => $item->updated_at->format($format),
                'description'   => $item->state->name,
            ]];
        });
    }



    public function getLastLogistic()
    {
        $date = $this->logistic_dates->sortByDesc('state.state')->first();

        return $date;
    }



    /**
     * Сумма детализации цены
     */
    public function getDetailingFullCostAttribute()
    {
        $price = $this->detailing_costs->sum('price');

        return $price;
    }



    /**
     * Получить данные указанного логистического статуса
     */
    public function getStateByName($key)
    {
        $this->load('logistic_dates');
        
        $date = $this->logistic_dates->where('logistic_system_name', $key)->first();
       
        if (!$date)
            return [];

        return [
            'author' => $date->author->cut_name ?? '',
            'date_at' => $date->date_at->format('d.m.Y'),
        ];
    }



    /**
     * Получить текущий статус логистики
     */
    public function currentCarState()
    {
        $stateService = new CarLogisticStateService($this);

        return $stateService->getStatusString();
    }



    /**
     * Проверка есть ли приходная накладная
     */
    public function isInvoice()
    {
        return ($this->getStateByName('invoice_date')) ? 1 : 0;
    }



    /**
     * Получить дату приходной накладной
     */
    public function getInvoiceDate()
    {
        return ($this->getLogisticDateByKey('invoice_date'));
    }



    /**
     * Получить статус резерва авто
     */
    public function getReserveStatus()
    {
        $diffDate = '';

        if($this->car_status_type->status == 'reserved' && $this->isReserved())
            $diffDate = $this->reserve->created_at->diffInDays(now()) + 1;
        
        return $this->car_status_type->get(suffix:$diffDate);
    }



    /**
     * Получить код цвета статуса резерва
     */
    public function getReserveStatusColor()
    {
        $status = explode(' ', $this->getReserveStatus())[0];

        return match ($status) {
            'Свободный' => 1,
            'Клиентский' => 2,
            'Резерв' => 3,
        };
    }



    /**
     * Проверка есть ли ПТС
     */
    public function hasPTS()
    {
        return $this->getLogisticDates('ransom_date') ? 1 : 0;
    }



    public function hasRansom()
    {
        return $this->hasPTS();
    }



    /**
     * Проверить есть ли резерв
     */
    public function isReserved(): bool
    {
        return $this->reserve ? 1 : 0;
    }



    /**
     * Проверка зафиксирована ли цена
     */
    public function isFixed(): bool
    {
        if ($this->isReserved())
            return $this->reserve->isFixedCost();
        return 0;
    }



    public function isFree()
    {
        return !$this->isReserved();
    }



    /**
     * Получить стоимость комплектации по контракту, если нет 0
     */
    public function getComplectationContractPrice()
    {
        if($this->isFixed())
            return $this->reserve->contract->complectation_price->sum('price') ?? 0;
        return 0;
    }



    /**
     * Получить стоимость комплектации
     */
    public function getComplectationPrice(): int
    {
        return $this->isFixed() ? $this->getComplectationContractPrice() : $this->complectation->price;
    }



    /**
     * Получить стоимость опций по контракту, если нет 0
     */
    public function getOptionContractPrice()
    {   
        if($this->isFixed())
            return $this->reserve->contract->option_price->sum('price') ?? 0;
        return 0;
    }



    /**
     * Получить стоимость опций
     */
    public function getOptionPrice(): int
    {
        return $this->isFixed() ? $this->getOptionContractPrice() : $this->options->sum('price');
    }



    /**
     * Получить стоимость переоценки
     */
    public function getOverPrice(): int
    {
        return $this->over_price->price ?? 0;
    }



    /**
     * Получить стоимость тюнинга
     */
    public function getTuningPrice(): float
    {
        $tuning = $this->tuning_price ? $this->tuning_price->price : 0;
        
        return $tuning;
    }



    /**
     * Получить стоимость подарка
     */
    public function getGiftPrice(): float
    {
        $gift = $this->gift_price ? $this->gift_price->price : 0;
        
        return $gift;
    }



    /**
     * Получить стоимость запчастей
     */
    public function getPartPrice(): float
    {
        $part = $this->part_price ? $this->part_price->price : 0;
        
        return $part;
    }



    /**
     * Получить всю стоимость тюнинга
     */
    public function getFullTuningPrice()
    {
        return $this->getTuningPrice() - $this->getGiftPrice();
    }



    /**
     * Получить стоимость машины, в том числе с учетом резерва
     */
    public function getCarPrice()
    {
        return $this->getTotalPrice() ? $this->getTotalPrice() :$this->getFullPrice();
    }



    /**
     * Получить полную стоимость автомобиля без оснований резерва
     */
    public function getFullPrice(): int
    {
        return array_sum([
            $this->getComplectationPrice(),
            $this->getOptionPrice(),
            $this->getOverPrice(),
            $this->getFullTuningPrice(),
        ]);
    }



    /**
     * Получить скидку из резерва
     */
    public function getReserveSale(): int
    {
        return $this->isReserved() ? $this->reserve->getSaleSum() : 0;
    }



    /**
     * Получить репарации от скидок
     */
    public function getSaleReparation()
    {
        return $this->isReserved() ? $this->reserve->getSaleReparation() : 0;
    }



    /**
     * Итоговая стоимость резерва 
     */
    public function getTotalPrice()
    {
        return $this->isReserved() ? $this->reserve->getTotalCost() : 0;
    }



    /**
     * Проверка выдана ли машина
     */
    public function isIssued(): bool
    {
        return $this->isReserved() ? $this->reserve->isIssued() : 0;
    }



    /**
     * Проверка продана ли машина
     */
    public function isSaled(): bool
    {
        return $this->isReserved() ? $this->reserve->isSaled() : 0;
    }



    /**
     * Получить дату выкупа у поставщика
     */
    public function getRansomDate(): string
    {
        return $this->getLogisticDates('ransom_date') ?? '';
    }



    /**
     * Получить дату списания у поставщика
     */
    public function getOffDate(): string
    {
        return $this->getLogisticDates('off_date') ?? '';
    }



    /**
     * Получить объект цены по которой комплектация машины будет продана
     */
    public function getComplectationCurrentPrice(): ComplectationPrice
    {
        if ($this->isFixed())
            return $this->reserve->contract->complectation_price->first();
        elseif ($this->complectation->current_price)
            return $this->complectation->current_price->curprice;

        throw new \Exception('Для этой комплектации, не найдена цена.');
    }



    /**
     * Получить массив цен опций установленных на авто
     */
    public function getOptionCurrentPrices()
    {
        if ($this->isFixed())
            return $this->reserve->contract->option_price;

        return collect($this->options->map(function ($item) {
            return $item->current_price;
        }));
    }



    /**
     * Получить дату оформления ДКП
     */
    public function getDKPDate(): string
    {
        if ($this->isFixed())
            return $this->reserve->getDKPDate();
        return '';
    }



    /**
     * Получить имя клиента
     */
    public function getClientName() : string
    {
        if($this->isReserved())
            return $this->reserve->getClientName();
        return '';
    }



    /**
     * Получить ID РЛ
     */
    public function getWorksheetId(): int
    {
        if ($this->isReserved())
            return $this->reserve->worksheet->id;
        return 0;
    }



    /**
     * Получить тип списания из резерва (желтый/зеленый)
     */
    public function getReportTypeStatus(): int
    {        
        if(!$this->owner)
            return 0;
        
        $ownerId = $this->owner->client_id;

        $isReserve = $this->isReserved();
        
        $isOwnerClient = $isReserve && ($ownerId == $this->reserve->worksheet->client_id);
        $isOwnerLisinger = $isReserve && ($ownerId == ($this->reserve->lisinger->first()->id ?? null));
        
        if($isOwnerClient || $isOwnerLisinger)
            return 1;

        return 2;
    }



    /**
     * Получить название типа списания (желтый/зеленый)
     */
    public function getReportTypeString(): string
    {
        $status = $this->getReportTypeStatus();

        if(array_key_exists($status, self::REPORT_STATUSES))
            return self::REPORT_STATUSES[$status];
        return '';
    }



    /**
     * Получить закуп
     */
    public function getPurchase()
    {
        return $this->purchase->cost ?? 0;
    }



    /**
     * Получить себестоимость доставки//$this->owner->client_id == $this->reserve->worksheet->client_id)
     */
    public function getDeliveryCost()
    {
        $res = 0;

        $res += $this->detailing_costs->sum(function ($item) {
            return $item->price * $item->coefficient;
        });

        return $res;
    }



    /**
     * Получить и закуп, и себестоимость доставки
     */
    public function getPurchaseWithDelivery()
    {
        $res = $this->getPurchase() + $this->getDeliveryCost();

        return $res;
    }



    /**
     * Проверить оприходована ли машина (На складже)
     */
    public function isOnStock(): string
    {
        return $this->getLogisticDates('stock_date') ? 1 : 0;
    }



    /**
     * Проверка что заяка
     */
    public function isApplication()
    {
        return $this->status == 'in_application' ? 1 : 0;
    }



    /**
     * Получить дату приемки на склад
     */
    public function getStockDate()
    {
        $date = $this->getLogisticDates('stock_date');

        return $date ? $date : '';
    }



    public function stockDate() : Carbon|null
    {
        $date = $this->logistic_dates->where('logistic_system_name', 'stock_date')->first();

        return $date ? $date->date_at : null;
    }



    public function getStatusCarForDiscountList() : array
    {
        if($this->isSaled())
            return [
                'status'    => 'Продан',
                'date'      => $this->reserve->sale->date_at->format('d.m.Y'),
                'created_at' => $this->reserve->sale->created_at->format('d.m.Y'),
                'author'    => $this->reserve->sale->decorator->cut_name,
            ];
        
        $date = $this->getLastLogistic();

        return [
            'status' => $this->state_status->description,
            'date' => $date->date_at->format('d.m.Y'),
            'created_at' => $date->created_at->format('d.m.Y'),
            'author' => $date->author->cut_name,
        ];
    }



    /**
     * GET COLOR IMAGE
     */
    public function getImageURLAttribute() : string
    {   
        if($this->image->first())
            return $this->image->first()->url;
        return 0;
    }



    /**
     * Получить сумму патежей клиента, если есть 
     */
    public function getPaymentSum() : int
    {
        if($this->isReserved())
            return $this->reserve->getPaymentSum();
        return 0;
    }



    /**
     * Автор резерва, если есть
     */
    public function getReserveAuthorName() : string
    {
        if($this->isReserved())
            return $this->reserve->getReserveAuthorName();
        return '';
    }



    /**
     * Получить менеджера который оформил продажу если есть
     */
    public function getSaleManager() : string
    {
        return $this->isSaled() ? $this->reserve->getSaleManager() : '';
    }



    /**
     * Получить дату продажи
     */
    public function getSaleDate() : string
    {
        return $this->isSaled() ? $this->reserve->getSaleDate() : '';
    }



    /**
     * Получить менеджера выдавшего автомобиль если есть
     */
    public function getIssueManager() : string
    {
        return $this->isIssued() ? $this->reserve->getIssueManager() : '';
    }



    /**
     * Получить дату выдачи
     */
    public function getIssueDate() : string
    {
        return $this->isIssued() ? $this->reserve->getIssueDate()->format('d.m.Y') : '';
    }



    /**
     * Получить вин номера трейдин машин
     */
    public function getTradinVIN() : array
    {        
        return $this->isReserved() ? $this->reserve->getTradeInVIN() : [];
    }



    /**
     * Получить вин номера трейдин машин в виде строки через запятую
     */
    public function getTradinVINString() : string
    {        
        return $this->isReserved() ? mb_strtoupper(StringHelper::arrayToStr($this->reserve->getTradeInVIN())) : '';
    }



    /**
     * Получить имя техника
     */
    public function getTechnicName()
    {
        return $this->technic ? $this->technic->user->cut_name : '';
    }



    /**
     * Получить номер заказа
     */
    public function getOrderNumber()
    {
        return $this->order ? $this->order->order_number : '';
    }



    /**
     * Вернуть сумму скидок только по виду скидки ЗН (discount_types.exported)
     */
    public function getExportedSaleSum()
    {
        if($this->isReserved())
            return $this->reserve->getExportedSaleSum();
        return 0;
    }
}
