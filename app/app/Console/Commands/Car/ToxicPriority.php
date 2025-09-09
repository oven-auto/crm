<?php

namespace App\Console\Commands\Car;

use App\Classes\Car\CarPriority\CarPriority;
use App\Http\Filters\CarFilter;
use App\Models\Car;
use Illuminate\Console\Command;

class ToxicPriority extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'car:toxic1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = [
            'logistic_statuses' => ['in_stock'],
            'type_statuses' => ['free','reserved']
        ];

        $query = Car::query()->select('cars.*');
        
        $filter = app()->make(CarFilter::class, ['queryParams' => $data]);
        
        $query->filter($filter);

        $query->chunk(20, function($chunkCars){
            $chunkCars->each(function($itemCar){
                CarPriority::make($itemCar)->checkPriority();
            });
        });
    }
}
