<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CME_GEARS extends Command
{
    private const ACRONYMS = [
        1 => 'at',
        2 => 'mt',
        3 => 'amt',
        6 => 'cvt',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cme:gear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get gears(transmissions) from CMExpert';

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
        print(':::::::::КПП:::::::::' . "\n\r");

        $token = \App\Classes\SMExpert\Token::getInstance()->getToken();

        $url = 'https://appraisal.api.cm.expert/v1/autocatalog/gears';
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->get($url, []);

        if (!$response->ok() && !isset($response['gears'])) {
            print("Не удалось получить данные по Api");
            return;
        }

        $current = \App\Models\MotorTransmission::get();

        $responseGears = collect($response['gears']);

        $responseGears->collect()->each(function ($item, $key) use ($current, $appendedCount) {

            $item = (object) $item;

            $appendedCount++;
            if (!$current->contains('id', $item->id)) {
                \App\Models\MotorTransmission::insert([
                    'id' => $item->id,
                    'name' => $item->text,
                    'acronym' => self::ACRONYMS[$item->id],
                ]);
                print ('Добавил тип КПП: ' . $item->text) . "\n\r";
            }
        });

        print('Получено: ' . $responseGears->count() . ' типов КПП' . "\n\r");
        print('В системе доступно: ' . $current->count() . ' типов КПП' . "\n\r");
        print('Добавлено: ' . $appendedCount . ' типов КПП' . "\n\r");
    }
}
