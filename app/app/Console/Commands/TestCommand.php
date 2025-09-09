<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Repositories\Car\Car\CarRepository;
use App\Services\Auth\AuthService;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test';

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
        $user = User::find(47);

        $repo = new CarRepository();

        dd(auth()::class);

        $str = '{
            "collector_id": 1,
            "mark_id": 3960,
            "brand_id": 348,
            "complectation_id": 32,
            "color_id": 13,
            "year": 2024,
            "order_number": "",
            "marker_id": 6,
            "trade_marker_id": 1,
            "provider_id": 1289,
            "provider_name": "АВТОВАЗ",
            "order_type_id": 2,
            "purchase_cost": "4444",
            "disable_sale": false,
            "disable_off": false,
            "options": [
                6,
                8,
                10
            ],
            "comment": "Заказала новую комплектацию вместо уходящей 21904-W5P-00",
            "delivery_term_id": 4,
            "detailing_costs": [],
            "tuning": {
                "price": "37580",
                "devices": [
                    1,
                    4
                ]
            }
        }';
        $str = (array)json_decode($str);

        $str['tuning'] = (array)$str['tuning'];


        for ($i = 0; $i <= 1000; $i++) {
            $str['order_number'] = $i . 'k';
            $str['year'] = rand(2020, 2024);
            $repo->store($str);
        }
    }
}
