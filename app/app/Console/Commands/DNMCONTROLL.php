<?php

namespace App\Console\Commands;

use App\Classes\LadaDNM\DNMAppealService;
use App\Classes\LadaDNM\DNMClientService;
use App\Classes\LadaDNM\DNMEvent;
use App\Classes\LadaDNM\DNMWorksheetService;
use App\Models\Client;
use App\Models\DnmWorksheetEvent;
use App\Models\WsmReserveNewCar;
use Illuminate\Console\Command;

class DNMCONTROLL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dnm:control';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $q = 'Укажите ID резерва';

        $id = $this->ask($q);

        $reserve = WsmReserveNewCar::withTrashed()->find($id);
        
        if(!$reserve)
            exit('Резерв не найден');

        print_r([
            '1' => 'Создать/Изменить клиента',
            '2' => 'Создать/Изменить РЛ',
            '3' => 'Создать/Изменить потребность',
            '4' => 'Создать/Изменить событие',
            '5' => 'Изменить статус события',
            '6' => 'Показать события резерва',
        ]);

        $action = $this->ask('Укажи номер действия');

        match($action) {
            '1' => $this->clientSend($reserve),
            '2' => $this->worksheetSend($reserve),
            '3' => $this->appealSend($reserve),
            '4' => $this->eventSend($reserve),
            '5' => $this->eventStatusSend($reserve),
            '6' => $this->eventShow($reserve),
            default => '',
        };
    }



    public function clientSend(WsmReserveNewCar $reserve)
    {
        (new DNMClientService())->save($reserve);
    }



    public function worksheetSend(WsmReserveNewCar $reserve)
    {
        (new DNMWorksheetService())->save($reserve->worksheet);
    }



    public function appealSend(WsmReserveNewCar $reserve)
    {
        (new DNMAppealService())->save($reserve);
    }



    public function eventSend(WsmReserveNewCar $reserve)
    {
        print_r([
            'visit'         => 'Визит',
            'reject'        => 'Отмена',
            'call'          => 'Звонок',
            'testdrive'     => 'ТестДрайв',
            'offer'         => 'Оффер',
            'internet'      => 'Интернет запрос',
            'contract'      => 'Контракт',
            'issue'         => 'Выдача',
        ]);

        $action = $this->ask('Введите действие');

        (new DNMEvent())->handler($reserve, $action);
    }



    public function eventStatusSend(WsmReserveNewCar $reserve)
    {
        $events = DnmWorksheetEvent::where('reserve_id', $reserve->id)->get();

        print('Резерв '.$reserve->id);

        print_r($events->map(function($item){
            return [
                'event_id' => $item->dnm_event_id,
                'status' => $item->status,
                'event_type' => $item->event_type,
                'created' => $item->created_at->format('d.m.Y H:i'),
            ];
        }));

        $eventId = $this->ask('Укажите event_id');

        $status = $this->ask('Укажите новый статус active/canceled/planed');

        (new DNMEvent())->update($eventId, $status);
    }



    public function eventShow(WsmReserveNewCar $reserve)
    {
        $events = DnmWorksheetEvent::where('reserve_id', $reserve->id)->get();

        print('Резерв '.$reserve->id);

        print_r($events->map(function($item){
            return [
                'event_id' => $item->dnm_event_id,
                'status' => $item->status,
                'event_type' => $item->event_type,
                'created' => $item->created_at->format('d.m.Y H:i'),
            ];
        }));
    }
}
