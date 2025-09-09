<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SQL_VIEW_OPTION_PRICE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql:optioncurrentprice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создать представление для хранения текущей актуальной цены опции';

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
        $query = "CREATE OR REPLACE VIEW option_current_prices AS
            SELECT op.id, op.price, op.option_id
            FROM option_prices as op
            WHERE op.id = (
                SELECT max(oo.id)
                FROM option_prices as oo
                WHERE oo.option_id = op.option_id
                and CURDATE() >= DATE_FORMAT(oo.begin_at, '%y-%m-%d')
        )";

        echo ('Создаю/Изменяю представление: OptionCarPrice' . "\r\n");
        echo ('Данное представление хранит текущую актуальную цену опции и содержит следующие столбцы:' . "\r\n");
        echo ('1) id - идентификатор из таблицы option_prices,' . "\r\n");
        echo ('2) price - собственно текущая актуальная по сегодняшней дате цена, ' . "\r\n");
        echo ('3) option_id - идентификатор опции из таблицы options' . "\r\n");

        \DB::statement($query);

        echo ('Закончил работу с OptionCarPrice' . "\r\n" . "\r\n");
    }
}
