<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("

            CREATE OR REPLACE VIEW wsm_credit_states AS (
                SELECT 
                    w_cred.id as wsm_credit_id,
                    CASE
                        WHEN 
                            w_cred.close = false AND 
                            w_calc.simple = false AND 
                            w_con.register_at = false
                            THEN 'work'
                        WHEN
                            w_cred.close = false AND 
                            w_calc.simple = false AND 
                            w_con.register_at = true
                            THEN 'issue'
                        WHEN
                            w_calc.simple = true AND 
                            w_con.register_at = false
                            THEN 'miss'
                        WHEN
                            w_cred.close = true
                            THEN 'close'
                        ELSE 
                            'work'
                    END as state

                FROM wsm_credits w_cred

                LEFT JOIN wsm_credit_contracts w_con 
                    on w_con.wsm_credit_id = w_cred.id

                LEFT JOIN wsm_credit_calculations w_calc 
                    on w_calc.wsm_credit_id = w_cred.id
            )

        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP VIEW IF EXISTS wsm_credit_states');
    }
};
