<?php

namespace App\Console\Commands\Permission;

use App\Models\Permission;
use Illuminate\Console\Command;

class AuditPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:audit-permission';

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
        $arr = [
            [
                'slug' => 'audit_crud',
                'name' => 'Аудиты: Создавать/изменять аудит'
            ],
            [
                'slug' => 'master_index',
                'name' => 'Аудиты: Открывать журнал'
            ],
            [
                'slug' => 'master_store',
                'name' => 'Аудиты: Проводить мастер-аудит',
            ],
            [
                'slug' => 'master_update',
                'name' => 'Аудиты: Изменять мастер-аудит',
            ],
            [
                'slug' => 'master_arbitr',
                'name' => 'Аудиты: Апеллировать мастер-аудит',
            ],
            [
                'slug' => 'master_delete',
                'name' => 'Аудиты: Удалять мастер-аудит',
            ],
            [
                'slug' => 'master_restore',
                'name' => 'Аудиты: Вернуть мастер-аудит',
            ],
            [
                'slug' => 'master_show',
                'name' => 'Аудиты: Просмотр мастер-аудит',
            ],
            [
                'slug' => 'assist_create',
                'name' => 'Аудиты: Проводить ассистент-аудит',
            ],
        ];

        foreach($arr as $item)
            Permission::updateOrCreate(
                ['slug' => $item['slug']],
                ['name' => $item['name']]
            );
    }
}
