<?php

namespace App\Repositories\Worksheet\Modules\Redemption;

use App\Classes\Telegram\Notice\TelegramNotice;
use App\Exceptions\Redemption\RedemptionException;
use App\Http\Filters\WSMRedemptionCarFilter;
use App\Models\RedemptionStatus;
use App\Models\UsedCar;
use App\Models\Worksheet;
use App\Models\WSMRedemptionCar;
use App\Models\WSMRedemptionLink;
use App\Models\WsmReserveNewCar;
use App\Repositories\Worksheet\DTO\RedemptionCreateDTO;
use App\Services\GetShortCutFromURL\GetShortCutFromURL;
use Illuminate\Support\Arr;
use \App\Services\Comment\Comment;
use App\Services\UsedCar\UsedCarService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * РЕПОЗИТОРИЙ ОЦЕНОК
 * - ПОЛУЧИТЬ ВСЕ ОЦЕНКИ ИЗ РАБОЧЕГО ЛИСТА
 * - ПОЛУЧИТЬ СПИСОК ОЦЕНОК ВВИДЕ ПАГИНАЦИИ, ПО ОПРЕДЕЛЕННЫМ ПАРАМЕТРАМ
 * - ПОЛУЧИТЬ КОЛ-ВО ОЦЕНОК, ПО ОПРЕДЕЛЕННЫМ ПАРАМЕТРАМ
 * - СОЗДАТЬ ОЦЕНКУ АВТОМОБИЛЯ В РАМАХ РЛ
 * - ИЗМЕНИТЬ ОЦЕНКУ
 * - ПЕРЕВЕСТИ МАШИНУ НА СКЛАД
 * - СОХРАНИТЬ СВОДНЫЕ ДАННЫЕ ОЦЕНКИ
 * - ПРОВЕРИТЬ МАШИНУ , ЧТО ОНА НЕ СОДЕРЖИТСЯ В ОЦЕНКЕ В РАМКАХ ОДНОГО РЛ
 * - СОХРАНИТЬ ССЫЛКУ В ОЦЕНКУ
 * - ЗАВЕРШИТЬ ОЦЕНКУ
 *
 * 16-01-2024
 *
 * - СПИСОК ВСЕХ КОММЕНТАРИЕВ
 * - ДОБАВИТЬ КОММЕНТАРИЙ
 * - ВЕРНУТЬ ОЦЕНКУ ЗАКРЫТУЮ (НЕ ВЫКУПЛЕННУЮ) В РАБОТУ
 */
class RedemptionRepository
{
    private $service;

    public function __construct(UsedCarService $service)
    {
        $this->service = $service;
    }



    /**
     * ПОЛУЧИТЬ ВСЕ ОЦЕНКИ ИЗ РАБОЧЕГО ЛИСТА
     * @param int $worksheet
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get(int $worksheet = 0): \Illuminate\Database\Eloquent\Collection
    {
        $query = WSMRedemptionCar::select('wsm_redemption_cars.*');

        $query->with([
            'offers', 'calculations', 'purchases', 'status', 'author',
            'client_car', 'car_sale_sign', 'links', 'status'
        ]);

        if ($worksheet)
            $query->where('worksheet_id', $worksheet);

        $redemptions = $query->get();

        return $redemptions;
    }



    /**
     * ПОЛУЧИТЬ СПИСОК ОЦЕНОК ВВИДЕ ПАГИНАЦИИ, ПО ОПРЕДЕЛЕННЫМ ПАРАМЕТРАМ
     * @param array $data
     * @param int $paginate
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function paginate(array $data, $paginate = 20): \Illuminate\Contracts\Pagination\Paginator
    {
        $query = WSMRedemptionCar::select('wsm_redemption_cars.*');

        $filter = app()->make(WSMRedemptionCarFilter::class, ['queryParams' => array_filter($data)]);

        $query->filter($filter);

        $query->with([
            'final_author', 'worksheet', 'last_offer', 'last_calculation', 'last_purchase',
            'status', 'author', 'client_car', 'car_sale_sign', 'links', 'status', 'client'
        ]);

        $result = $query->simplePaginate($paginate);

        return $result;
    }



    /**
     * ПОЛУЧИТЬ КОЛ-ВО ОЦЕНОК, ПО ОПРЕДЕЛЕННЫМ ПАРАМЕТРАМ
     * @param array $data
     * @return int
     */
    public function count(array $data): int
    {
        $subQuery = WSMRedemptionCar::select('wsm_redemption_cars.id');

        $filter = app()->make(WSMRedemptionCarFilter::class, ['queryParams' => array_filter($data)]);

        $subQuery->filter($filter);

        $res = DB::query()->fromSub($subQuery, 'subQ')->count();

        return $res;
    }



    /**
     * СОЗДАТЬ ОЦЕНКУ АВТОМОБИЛЯ В РАМАХ РЛ
     * @param Worksheet $worksheet
     * @param array $data
     * @return WSMRedemptionCar
     */
    public function store(Worksheet $worksheet, array $data): WSMRedemptionCar
    {
        $this->check_car($worksheet, $data['client_car_id']);

        $redemption = WSMRedemptionCar::create((new RedemptionCreateDTO($worksheet, $data))->get());

        $this->save($redemption, $data);

        Comment::add($redemption, 'create');

        return $redemption;
    }



    /**
     * ИЗМЕНИТЬ ОЦЕНКУ
     * @param WSMRedemptionCar $redemption
     * @param array $data
     * @return WSMRedemptionCar
     */
    public function update(WSMRedemptionCar $redemption, array $data): WSMRedemptionCar
    {
        //контрольная сумма новых данных
        $newSum = crc32(implode($data));
        //контрольная сумма старых данных
        $oldSum = crc32(implode([$redemption->expectation, $redemption->car_sale_sign_id, $redemption->redemption_type_id]));

        if($newSum == $oldSum)
            return $redemption;

        $clone = $redemption;

        $redemption->fill($data)->save();

        if ($redemption->author_id != auth()->user()->id)
        {
            $old = implode(' ', [
                $clone->type->name,
                '(' . $clone->car_sale_sign->name . '),',
                $clone->expectation . 'р.',
            ]);
            
            TelegramNotice::run($redemption)->update($old)->send([$redemption->author_id]);
        }

        return $redemption;
    }



    /**
     * Проверка машины на возможность добавления в CME
     */
    private function checkCar(WSMRedemptionCar $redemption)
    {
        if (!$redemption->last_purchase->price)
            throw new RedemptionException('fact_purchase');
        if ($redemption->redemption_status_id != 1)
            throw new RedemptionException('not_woking');
        if (!$redemption->client_car->vin)
            throw new RedemptionException('without_vin');
    }



    /**
     * ПЕРЕВЕСТИ МАШИНУ НА СКЛАД
     * @param WSMRedemptionCar $redemption
     * @return void
     */
    public function buyCar(WSMRedemptionCar $redemption): void
    {
        try {
            DB::transaction(function () use ($redemption) {
                $this->checkCar($redemption);//чекаем оценку на возможность выкупа 

                $redemption->setStatusOnStock();//меняем статус оценки на "На складе"

                $redemption->setFinalizer();//Записываем автор переноса авто на склад

                $this->service->createUsedCarFromRedmption($redemption);//создаем автомобиль на складе из авто клиента

                Comment::add($redemption, 'buy');

                $usersId = $redemption->worksheet->executors->where('id', '<>', Auth::id())->pluck('id');
                
                TelegramNotice::run($redemption)->buy()->send($usersId);
            }, 3);
        } catch(\Exception $e) {
            throw new RedemptionException($e->getMessage());
        }       
    }



    /**
     * СОХРАНИТЬ СВОДНЫЕ ДАННЫЕ ОЦЕНКИ (РАСЧЕТНАЯ ЦЕНА, ПРЕДЛОЖЕНИЕ, ФАКТ)
     * @param WSMRedemptionCar $redemption
     * @param array $data
     * @return void
     */
    public function save(WSMRedemptionCar $redemption, array $data, string $val = ''): void
    {
        if (isset($data['offer'])) {
            $redemption->saveOffer($data);
            $val = 'Оценка после диагностики ' . $data['offer'] . 'р.';
        }

        if (isset($data['price_begin']) && isset($data['price_end'])) {
            $redemption->savePrices($data);
            $val = 'Предварительная оценка ' . $data['price_begin'] . '-' . $data['price_end'] . 'р.';
        }

        if (isset($data['purchase'])) {
            $redemption->savePurchase($data);
            $val = 'Согласовано с клиентом ' . $data['purchase'] . 'р.';
        }

        if ($redemption->author_id != Auth::id())
            TelegramNotice::run($redemption)->calculate($val)->send([$redemption->author_id]);
    }



    /**
     * ПРОВЕРИТЬ МАШИНУ , ЧТО ОНА НЕ СОДЕРЖИТСЯ В ОЦЕНКЕ В РАМКАХ ОДНОГО РЛ
     * @param Worksheet $worksheet ID Рабочего листа
     * @param int $car_id ID машины клиента
     * @return void
     */
    private function check_car(Worksheet $worksheet, int $car_id): void
    {
        $check = WSMRedemptionCar::where('worksheet_id', $worksheet->id)
            ->where('client_car_id', $car_id)
            ->first();

        if ($check)
            throw new RedemptionException('is_working');
    }



    /**
     * СОХРАНИТЬ ССЫЛКУ В ОЦЕНКУ
     * @param WSMRedemptionCar $redemption
     * @param array $data
     * @return WSMRedemptionLink
     */
    public function saveLink(WSMRedemptionCar $redemption, array $data): WSMRedemptionLink
    {
        $link = $redemption->saveLink($data['url']);

        return $link;
    }



    /**
     * ЗАВЕРШИТЬ ОЦЕНКУ
     * @param WSMRedemptionCar $redemption
     * @return void
     */
    public function close(WSMRedemptionCar $redemption): void
    {
        if ($redemption->isClosed()) 
            throw new RedemptionException('not_woking');
        
        try{
            DB::transaction(function() use ($redemption){
                $redemption->setStatusClose();

                $redemption->setFinalizer();

                Comment::add($redemption, 'close');

                if ($redemption->worksheet->isWork())
                {
                    $usersId = $redemption->worksheet->executors->where('id', '<>', Auth::id())->pluck('id');
                    
                    TelegramNotice::run($redemption)->close()->send($usersId);
                }
            }, 3);
        } catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }



    /**
     *  СПИСОК ВСЕХ КОММЕНТАРИЕВ ОЦЕНКИ
     */
    public function getComments(WSMRedemptionCar $redemption)
    {
        return $redemption->comments->map(fn ($item) => [
            'text' => $item->text,
            'id' => $item->id,
            'author' => $item->author->cut_name,
            'created_at' => $item->created_at->format('d.m.Y (H:i)'),
        ]);
    }



    /**
     * ДОБАВИТЬ КОММЕНТАРИЙ
     */
    public function addComment(WSMRedemptionCar $redemption, string $text)
    {
        $redemption->saveComment($text);
    }



    /**
     * ВЕРНУТЬ ОЦЕНКУ ЗАКРЫТУЮ (НЕ ВЫКУПЛЕННУЮ) В РАБОТУ
     */
    public function revert(WSMRedemptionCar $redemption)
    {
        if ($redemption->isWorking())
            throw new RedemptionException('not_closing');

        try{
            DB::transaction(function() use($redemption){
                $redemption->setStatusWork();

                $redemption->deleteFinalizer();
            }, 3);
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
