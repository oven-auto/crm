<?php

namespace App\Console\Commands\Car;

use App\Classes\Car\CarPriority\CarPriority;
use App\Http\Filters\CarFilter;
use App\Models\Car;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class CarToxicStatuses extends Command
{
    private const ENTRY = [
        'has_ransom'                => 0,               //оплаты нет
        'only_free'                 => 1,               //только свободные
        'has_paid_date'             => 1,               //есть дата платного периода
        'has_controll_paid_date'    => 1,               //есть дата контроль оплаты
        'logistic_statuses'         => ['in_stock'],    //только авто со склада
    ];

    private const PROBLEM = [
        'only_free'                 => 1,               //только свободные
        'has_ransom'                => 1,               //оплата есть
        'logistic_statuses'         => ['in_stock'],    //только авто со склада
    ];

    private const STATUSES = [
        'preorder' => 1,                                //предзаказ
        'newentry' => 2,                                //свежее поступление
        'paidentry' => 3,                               //платный период
        'overdueentry' => 4,                            //просроченная дебиторка
        'problem' => 5,                                 //проблемный склад
        'toxic' => 6,                                   //токсичный склад

    ];

    protected $signature = 'car:toxic';

    protected $description = 'Command description';



    public function handle()
    {
        $time = env('NIGHT_COMMAND_TIME', '01:00');

        while(1)
        {
            if(
                now()->subMinutes(30)->format('H:i') <= $time &&
                now()->addMinutes(30)->format('H:i') >= $time
            )
                $this->exec();
            sleep(60*60);
        }
    }



    public function exec()
    {
        Log::channel('car_sale_stock')->alert('Скрипт "Статус приоритета продаж" запущен - '.now()->format('d.m.Y H:i'));
        $start = now();
        
        $this->entryCars();
        $this->problemCars();

        Log::channel('car_sale_stock')->alert('Скрипт "Статус приоритета продаж" исполнен - '.now()->format('d.m.Y H:i'));
        Log::channel('car_sale_stock')->alert('Затраченное время: '.$start->diffInSeconds(now()).'c.');
    }



    /**
     * ПОИСК ПО ПОСТУПЛЕНИЮ (СВЕЖЕЕ, ПЛАТНЫЙ ПЕРИОД, ПРОСРОК ДЕБИТОРКИ)
     */
    public function entryCars()
    {
        $query = Car::query()->select('cars.*');
        $query->with(['priority']); 
        $filter = app()->make(CarFilter::class, ['queryParams' => self::ENTRY]);
        $query->filter($filter);

        $this->newEntry(clone $query);
        $this->paidEntry(clone $query);
        $this->overdueEntry(clone $query);

        unset($query);
    }



    /**
     * ПОИСК ПО ПРОБЛЕМНЫМ (ПРОБЛЕМНЫЙ СКЛАД, ТОКСИЧНЫЙ СКЛАД)
     */
    public function problemCars()
    {
        $query = Car::query()->select('cars.*');
        $query->with(['priority']); 
        $filter = app()->make(CarFilter::class, ['queryParams' => self::PROBLEM]);
        $query->filter($filter);

        $this->problemStock(clone $query);
        $this->toxicStock(clone $query);

        unset($query);
    }



    /**
     * СВЕЖЕЕ ПОСТУПЛЕНИЕ
     */
    private function newEntry(Builder $query)
    {
        $query->where('car_paid_dates.date_at', '>', now());

        $query->where('car_controll_paid_dates.date_at', '>', now());
        
        $cars = $query->get();

        Log::channel('car_sale_stock')->alert('Найдено машин удовлетворяющих статусу "Свежее поступление" = '.$cars->count());
        
        foreach($cars as $car)
            if($car instanceof \App\Models\Car)
                CarPriority::make($car)->checkPriority();
    }



    /**
     * ПЛАТНЫЙ ПЕРИОД
     */
    public function paidEntry(Builder $query)
    {
        $query->where('car_paid_dates.date_at', '<=', now());

        $query->where('car_controll_paid_dates.date_at', '>=', now());
        
        $cars = $query->get();

        Log::channel('car_sale_stock')->alert('Найдено машин удовлетворяющих статусу "Платный период" = '.$cars->count());

        foreach($cars as $car)
            if($car instanceof \App\Models\Car)
                CarPriority::make($car)->checkPriority();
    }



    /**
     * ПРОСРОЧЕННАЯ ДЕБИТОРСКАЯ
     */
    public function overdueEntry(Builder $query)
    {
        $query->where('car_paid_dates.date_at', '<=', now());

        $query->where('car_controll_paid_dates.date_at', '<', now());
        
        $cars = $query->get();

        Log::channel('car_sale_stock')->alert('Найдено машин удовлетворяющих статусу "Просроченная дебиторка" = '.$cars->count());

        foreach($cars as $car)
            if($car instanceof \App\Models\Car)
                CarPriority::make($car)->checkPriority();
    }



    /**
     * ПРОБЛЕМНЫЙ СКЛАД
     */
    public function problemStock(Builder $query)
    {   
        $query->where('car_date_logistics.logistic_system_name', 'stock_date')
            ->whereDate('car_date_logistics.date_at', '>=', now()->subDays(90));
        
        $cars = $query->get();

        Log::channel('car_sale_stock')->alert('Найдено машин удовлетворяющих статусу "Проблемный склад" = '.$cars->count());

        foreach($cars as $car)
            if($car instanceof \App\Models\Car)
                CarPriority::make($car)->checkPriority();
    }



    /**
     * ТОКСИЧНЫЙ СКЛАД
     */
    public function toxicStock(Builder $query)
    {
        $query->where(function($group){
            $group->where('car_date_logistics.logistic_system_name', 'stock_date')
                ->whereDate('car_date_logistics.date_at', '<', now()->subDays(90));
        });        
        
        $cars = $query->get();

        Log::channel('car_sale_stock')->alert('Найдено машин удовлетворяющих статусу "Токсичный склад" = '.$cars->count());

        foreach($cars as $car)
            if($car instanceof \App\Models\Car)
                CarPriority::make($car)->checkPriority();
    }
}
