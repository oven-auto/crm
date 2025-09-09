<?php

namespace App\Console\Commands\DNM;

use App\Classes\LadaDNM\DNM;
use App\Models\Brand;
use App\Models\DnmBrand;
use Illuminate\Console\Command;

class DnmGetBrands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dnm:brands';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dnm = DNM::init();

        $brands = $dnm->getBrands();

        foreach($brands as $item)
        {
            $name = $item['name'];

            $brand = Brand::where('name', 'like', '%'.$name.'%')->first();

            if($brand)
                DnmBrand::updateOrcreate(
                    ['brand_id' => $brand->id],
                    ['dnm_brand_id' => $item['id'],]
                );
        }
    }
}
