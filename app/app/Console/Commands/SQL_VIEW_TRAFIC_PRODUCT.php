<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SQL_VIEW_TRAFIC_PRODUCT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql:traficprod';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создаст представление для отображения всех моделей, продуктов и услуг для трафика';

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
        $query = "CREATE OR REPLACE view trafic_products as
        SELECT concat ('service',service_products.id) as number,
                    service_products.id as uid,
                    'App\\Models\\ServiceProduct' as model,
                    name,
                    service_product_appeals.appeal_id as appeal_id,
                    company_id,
                    duration,
                    price,
                    description,
                    group_id
                FROM service_products
                LEFT JOIN service_product_appeals on service_product_appeals.service_product_id = service_products.id

                UNION SELECT 
                    concat('mark', m.id) as number,
                    m.id as uid,
                    'App\\Models\\Mark' as model,
                    m.name as name,
                    12 as appeal_id,
                    cb.company_id as company_id,
                    0 as duration,
                    IFNULL(min(cprice.price),0) as price,
                    NULL as description,
                    NULL as group_id
                FROM marks as m
                LEFT JOIN company_brands as cb on cb.brand_id = m.brand_id
                LEFT JOIN complectations as comp on comp.mark_id = m.id
                LEFT JOIN complectation_current_prices as cprice on cprice.complectation_id = comp.id
                LEFT JOIN brands on brands.id = m.brand_id
                WHERE brands.diller = 1 and m.diller_status = 1
                GROUP BY m.id";

        DB::statement($query);
    }
}



// --     concat('model',m.id) as number,
// --     m.id as uid,
// --     'App\\Models\\Marks' as model,
// --     m.name as name,
// --     12 as appeal_id,
// --     cb.company_id,
// --     0 as duration,
// --     NULL /*if(min(complectations.price)is null, 0, min(complectations.price))*/ as price,
// --     NULL as description,
// --     NULL as group_id
// -- FROM marks m
// --     LEFT JOIN brands b on m.brand_id = b.id
// --     LEFT JOIN company_brands cb on b.id = cb.brand_id
// --     /*LEFT JOIN complectations on m.id = complectations.mark_id*/
// --    WHERE cb.brand_id IS NOT NULL
// --    AND m.status > 0 AND diller_status = 1
// -- GROUP BY m.id,cb.company_id
// -- UNION SELECT