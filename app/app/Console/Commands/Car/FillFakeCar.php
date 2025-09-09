<?php

namespace App\Console\Commands\Car;

use App\Models\Car;
use App\Models\Complectation;
use App\Models\LogisticState;
use App\Models\Option;
use App\Models\OrderType;
use App\Models\Payment;
use App\Models\TradeMarker;
use App\Models\TraficZone;
use App\Models\Worksheet;
use App\Models\WsmReserveNewCarContract;
use App\Models\WsmReservePayment;
use App\Repositories\Car\Car\CarRepository;
use App\Repositories\Car\Car\DTO\LogisticDateDTO;
use App\Repositories\Worksheet\Modules\Reserve\ReserveContractRepository;
use App\Repositories\Worksheet\Modules\Reserve\ReservePaymentRepository;
use App\Repositories\Worksheet\Modules\Reserve\ReserveRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FillFakeCar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fake-car';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $carRepo;

    private $reserveRepo;

    private $contractRepo;

    private $payRepo;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        CarRepository $carRepo, 
        ReserveRepository $reserveRepo, 
        ReserveContractRepository $contract,
        ReservePaymentRepository $payRepo
    )
    {
        parent::__construct();
        $this->carRepo = $carRepo;
        $this->reserveRepo = $reserveRepo;
        $this->contractRepo = $contract;
        $this->payRepo = $payRepo;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $carCount = 100;

        $progressBar = $this->output->createProgressBar($carCount);
        $progressBar->start();

        Auth::attempt([
            'email' => 'oit@oven-auto.ru',
            'password' => 'Jdty2019'
        ]);
        
        DB::statement('DELETE FROM cars');
        DB::statement('DELETE FROM wsm_reserve_new_cars');
        
        $complectations = Complectation::with(['mark.colors', 'current_price'])->get();

        $options = Option::with('current_price')->get()->filter(function($item){
            if($item->current_price->price)
                return $item;
        });
        
        $tradeMarkers = TradeMarker::get();

        $orderTypes = OrderType::get();

        $logisticStatuses = LogisticState::orderBy('state')->get()->filter(function($item){
            if(!in_array($item->system_name, ['sale_date','ransom_date', 'issue_date', 'off_date', 'presale_date']))
                return $item;
        })->values();
       
        $complectations = $complectations->filter(function($item){
            if($item->current_price->price && $item->mark->colors->count())
               return $item;
        })->map(function($item){
            return [    
                'complectation_id' => $item->id,
                'mark_id' => $item->mark->id,
                'brand_id' => $item->mark->brand->id,
                'author_id' => Auth::id(),
                'color_id' => $item->mark->colors->random()->id,
                'year' => rand(2022,2024),
                'vin' => '',
                'disable_off' => (bool)rand(0,2)
            ];
        });
        
        for($i =0; $i<$carCount; $i++)
        {
            $progressBar->advance();
            $currentComplectation = $complectations->random();
            $currentComplectation['vin'] = static::getRandomStr(4);
            $car = Car::create($currentComplectation);
            $car->saveProvider(1289);
            $car->saveTradeMarker($tradeMarkers->random()->id);
            $car->saveOptions($options->where('mark_id', $car->mark_id)->map(function($item){
                return $item->id;
            })->toArray());
            $car->over_price()->create([
                'price' => rand(1,5) * 10000,
                'author_id' => Auth::id(),
            ]);

            $tuningPrice = rand(0, 5) * 10000;
            $car->saveTuningPrice($tuningPrice);

            $car->saveGiftPrice(rand(0, $tuningPrice/10000) * 10000);

            $car->saveOrderNumber(date('dmYHi').rand(1000,9999));

            $car->saveOrderType($orderTypes->random()->id);

            $dates = [];
            $countState = rand(3, count($logisticStatuses));
            for($z=0; $z<$countState; $z++)
                $dates[$logisticStatuses[$z]->system_name] = date('d.m.Y');

            $car->saveLogisticDates(new LogisticDateDTO($dates));

            $car->refresh();
            
            $this->carRepo->setCarStatus($car);

            $isReserved = rand(0,3);
            if($car->isOnStock() || $isReserved == 3)
            {
                $worksheet = Worksheet::query()->inRandomOrder()->first();

                $worksheet->client->fill([
                    'firstname' => 'Олег',
                    'lastname' => 'Олегов',
                    'fathername' => 'Олегович',
                    'trafic_zone_id' => TraficZone::query()->inRandomOrder()->first()->id,
                    'client_type_id' => 1,
                    'trafic_sex_id' => 2,
                ])->save();

                $worksheet->client->passport->fill([
                    'birthday_at' => '1990-01-01',
                    'address' => 'Test'
                ])->save();

                $worksheet->load('client');
                $worksheet->refresh();


                $this->reserveRepo->createReserve([
                    'car_id' => $car->id,
                    'worksheet_id' => $worksheet->id,
                ]);
            }
            
            $car->refresh();

            if($car->isOnStock())
            {
                $data = [
                    'dkp_offer_at' => date('d.m.Y'),
                    'dkp_decorator_id' => Auth::id(),
                    'reserve_id' => $car->reserve->id
                ];
                $contract = new WsmReserveNewCarContract();
                $this->contractRepo->create($contract, $data);
                $pay = new WsmReservePayment();
                $this->payRepo->save($pay, [
                    'reserve_id' => $car->reserve->id,
                    'payment_id' => Payment::first()->id,
                    'amount' => 10000,
                    'date_at'   => date('d.m.Y'),
                ]);
            }
        }
        $progressBar->finish();
    }



    private static function getRandomStr($count = 4) {
        $result = '';

        $array = array_merge(range('a','z'), range('0','9'));

        for($i = 0; $i < $count; $i++) {
            $result .= $array[rand(0, 35)];
        }

        $result = date('dmyHiv') . $result;
        
        return $result;
    }
}
