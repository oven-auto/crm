<?php

namespace App\Repositories\Client;

use App\Classes\Telegram\Notice\TelegramNotice;
use App\Http\DTO\Client\ClientEvent\ClientEventCreateDTO;
use App\Http\Filters\ClientEventFilter;
use App\Http\Filters\ClientEventListFilter;
use App\Models\ClientEvent;
use App\Models\ClientEventStatus;
use App\Services\Client\EventExecutorReporterService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientEventRepository
{
    public function __construct(
        private EventExecutorReporterService $service
    )
    {
        
    }



    public function getStatusById(int $id)
    {
        return ClientEventStatus::with('event')->findOrFail($id);
    }



    private function saveEvent(ClientEvent &$event, ClientEventCreateDTO $dto)
    {
        $event->fill($dto->getEventData());

        $event->save();
    }



    private function saveEventStatus(ClientEvent &$event, ClientEventCreateDTO $dto)
    {
        $event->statuses()->updateOrCreate(
            ['id' => $event->lastStatus->id],
            $dto->getStatusData()
        );

        $event->load('lastStatus');
    }



    private function saveComment(ClientEvent &$event, ClientEventCreateDTO $dto)
    {
        if(!$dto->text)//тут пиздец натворили, короче когда то коммент обязателен, конда то нет. 
            return;

        $event->comments()->create([
            'text' => $dto->text,
            'author_id' => Auth::id(),
            'client_event_status_id' => $event->lastStatus->id
        ]);

        TelegramNotice::run($event->lastStatus)->comment()->send(
            $event->lastStatus->executors->pluck('id')->toArray()
        );
    }



    private function save(ClientEvent &$event, ClientEventCreateDTO $dto)
    {
        DB::transaction(function() use($dto, $event){
            $this->saveEvent($event, $dto);
        
            $this->saveEventStatus($event, $dto);

            $this->service->append($event->lastStatus, Auth::id());

            $this->saveComment($event, $dto);
        }, 3);
    }



    public function create(ClientEventCreateDTO $dto)
    {   
        $event = app()->make(\App\Models\ClientEvent::class); 

        $event->fill(['author_id' => Auth::id(), 'resolve' => 0]);

        $this->save($event, $dto);
        
        return $event->lastStatus;
    }



    public function update(int $statusId, ClientEventCreateDTO $dto)
    {
        $event = $this->getStatusById($statusId)->event; 

        $this->save($event, $dto);
        
        return $event->lastStatus;
    }



    public function getAllInGroupByClientId(int $clientId)
    {
        $res = ClientEventStatus::select('client_event_statuses.*')
            ->leftJoin('client_events','client_events.id','client_event_statuses.event_id')
            ->where('client_events.client_id', $clientId)
            ->where('client_event_statuses.confirm', 'waiting')
            ->with(['event'])
            ->orderBy('client_event_statuses.date_at')
            ->get();

        return $res;
    }



    public function counter(Array $data) : int
    {
        $query = ClientEventStatus::query();

        $subQuery = ClientEventStatus::query();

        $filter = app()->make(ClientEventFilter::class, ['queryParams' => array_filter($data)]);

        $subQuery->filter($filter);

        $subQuery->OnlyTableData()->ListOrder();

        $query->rightJoinSub($subQuery, 'subQuery', function($join){
            $join->on('subQuery.id','=','client_event_statuses.id');
        });

        $result = $query->count();

        return $result;
    }



    public function paginate(Array $data, $paginate = 15)
    {
        $query = ClientEventStatus::query();

        $filter = app()->make(ClientEventFilter::class, ['queryParams' => array_filter($data)]);

        $query->filter($filter);

        $query->OnlyTableData()->WithEventAndTrafic()->ListOrder();

        $result = $query->simplePaginate($paginate);

        return $result;
    }



    public function get(Array $data)
    {
        $query = ClientEventStatus::query();

        $filter = app()->make(ClientEventFilter::class, ['queryParams' => array_filter($data)]);

        $query->filter($filter);

        $query->OnlyTableData()->WithEventAndTrafic()->ListOrder();

        $result = $query->get();

        return $result;
    }



    public function getEventsForTaskList(array $data)
    {
        $query = ClientEventStatus::query();

        $filter = app()->make(ClientEventListFilter::class, ['queryParams' => array_filter($data)]);

        $query->filter($filter);

        $query->OnlyTableData()->WithEventAndTrafic()->ListOrder();

        $result = $query->get();

        return $result;
    }    
}

