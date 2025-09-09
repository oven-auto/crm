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

            CREATE OR REPLACE VIEW wsm_service_states AS (
                SELECT 
                    w_ser.id as wsm_service_id,
                    CASE
                        WHEN 
                            w_ser.simple = false AND 
                            w_con.register_at = false
                            THEN 'work'
                        WHEN
                            w_ser.simple = false AND 
                            w_con.register_at = true
                            THEN 'issue'
                        WHEN
                            w_ser.simple = true AND 
                            w_con.register_at = false
                            THEN 'miss'
                        ELSE 
                            'work'
                    END as state

                FROM wsm_services w_ser

                LEFT JOIN wsm_service_contracts w_con 
                    on w_con.wsm_service_id = w_ser.id
            )

        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP VIEW IF EXISTS wsm_service_states');
    }
};
