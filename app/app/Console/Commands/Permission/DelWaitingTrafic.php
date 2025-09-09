<?php

namespace App\Console\Commands\Permission;

use App\Classes\LadaDNM\DNM;
use App\Models\Permission;
use Illuminate\Console\Command;

class DelWaitingTrafic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:add';

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
        $arr = [
            [
                'slug' => 'trafic_delete_waiting_author',
                'name' => 'Трафик: Удалить ожидающий (где я автор)'
            ],
            [
                'slug' => 'trafic_close_waiting_author',
                'name' => 'Трафик: Упустить ожидающий (где я автор)'
            ],
        ];

        foreach($arr as $item)
            Permission::updateOrCreate(
                ['slug' => $item['slug']],
                ['name' => $item['name']]
            );
    }
}
