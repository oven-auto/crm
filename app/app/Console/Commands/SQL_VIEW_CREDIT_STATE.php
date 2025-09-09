<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SQL_VIEW_CREDIT_STATE extends Command
{
    protected $signature = 'sql:credit-state';

    protected $description = 'Command description';

    

    public function handle()
    {
        $query = "CREATE OR REPLACE VIEW wsm_credit_states AS (
                SELECT 
                    w_credit.id as wsm_credit_id,
                    CASE
                        WHEN 
                            w_con.register_at IS NULL AND
                            w_calc.simple = 0
                            THEN 'work'
                        WHEN 
                            w_con.register_at IS NOT NULL
                            THEN 'issue'
                        WHEN
                            w_calc.simple = 1
                            THEN 'miss'
                        ELSE 
                            'work'
                    END as state

                FROM wsm_credits w_credit

                LEFT JOIN wsm_credit_calculations w_calc 
                    on w_calc.wsm_credit_id = w_credit.id
                LEFT JOIN wsm_credit_contracts w_con
                    on w_con.wsm_credit_id = w_credit.id
        )";

        DB::statement($query);
    }
}
