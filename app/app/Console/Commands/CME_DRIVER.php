<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CME_DRIVER extends Command
{
    private const ACRONYMS = [
        2 => 'rwd',
        1 => 'fwd',
        5 => 'awd'
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cme:driver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get drives from CMExpert';

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
        $appendedCount = 0;

        print("\n\r");
        print(':::::::::ПРИВОДЫ:::::::::' . "\n\r");

        $token = \App\Classes\SMExpert\Token::getInstance()->getToken();

        $url = 'https://appraisal.api.cm.expert/v1/autocatalog/drives';
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->get($url, []);

        if (!$response->ok() && !isset($response['drives'])) {
            print("Не удалось получить данные по Api");
            return;
        }

        $current = \App\Models\MotorDriver::get();

        $responseDrivers = collect($response['drives']);

        $responseDrivers->collect()->each(function ($item, $key) use ($current, $appendedCount) {

            $item = (object) $item;

            if (!$current->contains('id', $item->id)) {
                \App\Models\MotorDriver::insert([
                    'id' => $item->id,
                    'name' => $item->text,
                    'acronym' => self::ACRONYMS[$item->id],
                ]);
                print ('Добавил тип привода: ' . $item->text) . "\n\r";
            }
        });

        print('Получено: ' . $responseDrivers->count() . ' типов привода' . "\n\r");
        print('В системе доступно: ' . $current->count() . ' типов привода' . "\n\r");
        print('Добавлено: ' . $appendedCount . ' типов привода' . "\n\r");
    }
}
