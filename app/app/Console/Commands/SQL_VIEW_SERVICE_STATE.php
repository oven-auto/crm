<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SQL_VIEW_SERVICE_STATE extends Command
{
    protected $signature = 'sql:service-state';   
    
    protected $description = 'Command description';
    
    public function handle()
    {
        $query = "CREATE OR REPLACE VIEW wsm_service_states AS (
                SELECT 
                    w_ser.id as wsm_service_id,
                    CASE
                        WHEN 
                            w_ser.simple = 0 AND 
                            w_con.register_at IS NULL
                            THEN 'work'
                        WHEN 
                            w_con.register_at IS NOT NULL
                            THEN 'issue'
                        WHEN
                            w_ser.simple = 1
                            THEN 'miss'
                        ELSE 
                            'work'
                    END as state

                FROM wsm_services w_ser

                LEFT JOIN wsm_service_contracts w_con 
                    on w_con.wsm_service_id = w_ser.id
        )";

        DB::statement($query);
    }
}
