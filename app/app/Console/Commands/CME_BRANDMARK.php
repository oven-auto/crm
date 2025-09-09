<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Brand;
use App\Models\Mark;
use Illuminate\Support\Str;

class CME_BRANDMARK extends Command
{
    private const URL_BRAND = 'https://appraisal.api.cm.expert/v1/autocatalog/brands';
    private const URL_MODEL = 'https://appraisal.api.cm.expert/v1/autocatalog/models';
    private static $token;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cme:brand';

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
        self::$token = \App\Classes\SMExpert\Token::getInstance()->getToken();
    }


    private function makeResponse(string $url, array $param = [])
    {
        $response = Http::withHeaders([
            'Authorization' => self::$token
        ])->get($url, $param);

        return $response;
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle($current = 0, $appendedCount = 0, $appendedMarkCount = 0, $modelCMECount = 0, $ourModelCount = 0)
    {
        print("\n\r");
        print(':::::::::БРЭНДЫ И МОДЕЛИ:::::::::' . "\n\r");

        $response = $this->makeResponse(self::URL_BRAND);

        $countCME = count($response['brands']);

        $brands = Brand::get();

        foreach ($response['brands'] as $key => $item) {
           
            if (!$brands->contains('uid', $item['id'])) {
               Brand::create([
                   'uid' => $item['id'],
                   'name' => $item['text'],
                   'slug' => Str::slug($item['text'])
               ]);

               $appendedCount++;
            }

            $responseModel = $this->makeResponse(self::URL_MODEL, ['brand' => $item['id']]);

            $modelCMECount += count($responseModel['models']);

            $brand = Brand::where('uid', $item['id'])->first();

            $models = Mark::where('brand_id', $brand->id)->get();

            $ourModelCount += $models->count();
            
            $file = fopen('cme.txt', 'a');
            fwrite($file, json_encode($responseModel['models']));

            foreach ($responseModel['models'] as $itemModel)
            	if($brand->uid == 87)
                    
		    	
                if (!$models->contains('uid', $itemModel['id'])) {
                    Mark::create([
                        'uid' => $itemModel['id'],
                        'name' => $itemModel['text'],
                        'slug' => Str::slug($itemModel['text']),
                        'brand_id' => $brand->id,
                        'brand_uid' => $brand->uid,
                        'status' => 0
                    ]);
              
                    $appendedMarkCount++;
                }

            if ($key == 0)
                echo "0%\n";

            if (round(($key / $countCME) * 100) % 10 === 0) {
                if ($current != round(($key / $countCME) * 100)) {
                    $current = round(($key / $countCME) * 100);
                    for ($i = 0; $i < $current / 10; $i++)
                        echo '*';
                    echo round(($key / $countCME) * 100) . "%\n";
                }
            }
        }
        echo "\n";
        echo "От СМЕ получено {$countCME} брендов\n";
        echo "От СМЕ получено {$modelCMECount} моделей\n";
        echo "В нашей базе было {$brands->count()} брендов\n";
        echo "В нашей базе было {$ourModelCount} брендов\n";
        echo "Добавлено {$appendedCount} брендов\n";
        echo "Добавлено {$appendedMarkCount} моделей\n";

        fclose($file);
    }
}
