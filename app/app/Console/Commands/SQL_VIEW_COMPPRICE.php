<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SQL_VIEW_COMPPRICE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql:comprice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создать представлние для получения текущей цены комплектации';

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
        $query = "
            CREATE OR REPLACE VIEW complectation_current_prices AS
            SELECT cp.id, cp.price, cp.complectation_id, cp.begin_at
            FROM complectation_prices as cp
            WHERE cp.id = (
                SELECT max(c.id)
                FROM complectation_prices as c
                WHERE c.complectation_id = cp.complectation_id
                and CURDATE() >= DATE_FORMAT(c.begin_at, '%y-%m-%d')
            )
        ";

        echo ('Создаю/Изменяю представление: ComplectationCurrentPrice' . "\r\n");
        echo ('Данное представление хранит текущую актуальную цену комплектации и содержит следующие столбцы:' . "\r\n");
        echo ('1) id - идентификатор из таблицы complectation_prices,' . "\r\n");
        echo ('2) price - собственно текущая актуальная по сегодняшней дате цена, ' . "\r\n");
        echo ('3) complectation_id - идентификатор опции из таблицы options' . "\r\n");

        DB::statement($query);

        echo ('Закончил работу с ComplectationCurrentPrice' . "\r\n" . "\r\n");
    }
}
