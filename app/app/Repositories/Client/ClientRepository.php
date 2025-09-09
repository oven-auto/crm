<?php

namespace App\Repositories\Client;

use App\Helpers\Date\DateHelper;
use App\Http\DTO\Client\ClientDTO;
use App\Models\Client;
use App\Http\Filters\ClientFilter;
use App\Models\Trafic;
use Illuminate\Support\Arr;
use App\Models\ClientPassport;
use Illuminate\Support\Facades\DB;

/**
 * Репазиторий модели Client
 */
class ClientRepository
{
    /**
     * Метод задает запрос на получение списка клиентов удовлетворяющих заданным свойствам фильтра
     * @param array $data данные для фильтра
     * @return \Illuminate\Database\Eloquent\Builder $query \Illuminate\Database\Eloquent\Builder
     */
    private function filter($data = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = Client::select('clients.*');
        $filter = app()->make(ClientFilter::class, ['queryParams' => array_filter($data)]);
        return $query
            ->filter($filter);
    }



    public function find(array $data)
    {
        $data['phone'] = 7 . mb_substr($data['phone'], 1);

        $query = Client::select('clients.*');

        $filter = app()->make(ClientFilter::class, ['queryParams' => array_filter($data)]);

        $query->filter($filter);

        $query->with(['latest_worksheet', 'phones', 'emails', 'cars', 'inn', 'zone', 'sex']);

        $client = $query->firstOrFail();

        return $client;
    }



    /**
     * Метод возращает постраничную коллекию клиентов, прошедших фильтрацию
     * @param array $data данные для фильтра
     * @param integer $paginate не обязательное поле, по умолчанию 10
     * @return \Illuminate\Contracts\Pagination\Paginator $result \Illuminate\Contracts\Pagination\Paginator
     */
    public function paginate($data = [], $paginate = 10): \Illuminate\Contracts\Pagination\Paginator
    {
        $query = $this->filter($data);
        $query->with(['latest_worksheet', 'phones', 'emails', 'cars', 'inn', 'zone', 'sex']);
        $query->withCount(['unionsChildren', 'unionsParent']);
        $query->groupBy('clients.id');
        $result = $query->simplePaginate($paginate);
        return $result;
    }



    /**
     * Метод возращает постраничную коллекию клиентов, прошедших фильтрацию
     * @param array $data данные для фильтра
     * @param integer $paginate не обязательное поле, по умолчанию 10
     * @return \Illuminate\Contracts\Pagination\Paginator $result \Illuminate\Contracts\Pagination\Paginator
     */
    public function get($data = [], $length = 10)
    {
        $query = $this->filter($data);
        $query->with(['latest_worksheet', 'phones', 'emails', 'cars', 'inn', 'zone', 'sex',]);
        $query->groupBy('clients.id');
        $result = $query->limit($length)->get();
        return $result;
    }



    public function export($data = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->filter($data);
        $result = $query->get();
        return $result;
    }



    public function save(Client $client, ClientDTO $dto): Client
    {  
        $client = DB::transaction(function() use($client, $dto){
            $client->fill((array) $dto->client)->save();

            $client->passport()->updateOrCreate(['client_id' => $client->id],  (array) $dto->passport);

            if($dto->inn)
                $client->inn()->updateOrCreate(['client_id' => $client->id],['number' => $dto->inn]);
            
            $client->phones()->delete();
            
            $client->phones()->createMany($dto->phones);
            
            $client->emails()->delete();
        
            $client->emails()->createMany(array_map(fn($item) => ['email' => $item], $dto->emails));
 
            $client->refresh();

            return $client;
        }, 3);
        
        return $client;
    }

    

    public function findOrCreate(Trafic $trafic): Client
    {
        $query = Client::select('clients.*')->with('phones')
            ->leftJoin('client_phones', 'client_phones.client_id', 'clients.id')
            ->leftJoin('client_inns', 'client_inns.client_id', 'clients.id');
        
        match($trafic->client->client_type_id) {
            1 => $query->where('client_phones.phone', $trafic->client->phone),
            2 => $query->where('client_inns.number', $trafic->client->inn),
            3 => $query->where('client_inns.number', $trafic->client->inn),
            default => '',
        };
        
        $client = $query->first();

        if (!$client)
            $client = $this->save(new Client(), ClientDTO::fromTrafic($trafic));

        return $client;
    }



    public static function getClientFromTrafic(Trafic $trafic)
    {
        $me = new self;

        return $me->findOrCreate($trafic);
    }



    /**
     * Удалить мягко клиента
     * @param Client $client Client
     * @return void
     */
    public function delete(Client $client): void
    {
        if ($client->client_type_id == 2)
            throw new \Exception('Нельзя удалять юр.лицо');

        $client->phones()->delete();

        $client->delete();
    }


    
    /**
     * Метод возращает количество клиентов, удовлетворяющих условию фильтра
     * @param array $data данные для фильтра
     * @return int $result int
     */
    public function counter($data = []): int
    {
        $query = Client::select(DB::raw('count(clients.id)'));

        $filter = app()->make(ClientFilter::class, ['queryParams' => array_filter($data)]);

        $query->filter($filter)->groupBy('clients.id');

        $result = $query->get()->count();

        return $result;
    }
}
