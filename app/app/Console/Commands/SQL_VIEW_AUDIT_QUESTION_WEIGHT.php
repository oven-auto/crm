<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SQL_VIEW_AUDIT_QUESTION_WEIGHT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql:view.audit_weights';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создать представление хранящее оценку вопроса аудита у которого не указан weight';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = "CREATE OR REPLACE VIEW audit_weights AS
            SELECT 
                a.id as audit_id,
                (100-IFNULL(sum(aq.weight),0))/(count(aq.id)-sum(IF(aq.weight is NULL,0,1))) as weight
                FROM audits a
                left join audit_questions aq on aq.audit_id  = a.id
                WHERE aq.deleted_at is null
                GROUP BY a.id";

        DB::statement($query);

        $this->info('Команда выполнена успешно!');
    }
}
