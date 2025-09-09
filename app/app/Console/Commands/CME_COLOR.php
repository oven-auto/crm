<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CME_COLOR extends Command
{
    private const ACRONYMS = [
        16 => 'black',
        13 => 'gray',
        12 => 'silver',
        2 => 'white',
        1 => 'beige',
        4 => 'yellow',
        6 => 'gold',
        9 => 'orange',
        7 => 'brown',
        8 => 'red',
        11 => 'pink',
        15 => 'violet',
        10 => 'purple',
        14 => 'blue',
        3 => 'skyblue',
        5 => 'green',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cme:color';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get colors from CMExpert';

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
        print(':::::::::ПАЛИТРА ЦВЕТОВ:::::::::' . "\n\r");

        $token = \App\Classes\SMExpert\Token::getInstance()->getToken();

        $url = 'https://appraisal.api.cm.expert/v1/autocatalog/colors';
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->get($url, []);

        if (!$response->ok() && !isset($response['colors'])) {
            print("Не удалось получить данные по Api");
            return;
        }

        $current = \App\Models\Color::get();

        $responseGears = collect($response['colors']);

        $responseGears->collect()->each(function ($item, $key) use ($current, $appendedCount) {

            $item = (object) $item;

            $appendedCount++;

            if (!$current->contains('id', $item->id)) {
                \App\Models\Color::insert([
                    'id' => $item->id,
                    'name' => $item->text,
                    'acronym' => self::ACRONYMS[$item->id],
                ]);
                print ('Добавил цвет: ' . $item->text) . "\n\r";
            }
        });

        print('Получено: ' . $responseGears->count() . ' цветов' . "\n\r");
        print('В системе доступно: ' . $current->count() . ' цветов' . "\n\r");
        print('Добавлено: ' . $appendedCount . ' цветов' . "\n\r");
    }
}
