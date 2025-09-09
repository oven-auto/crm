<?php

namespace App\Console\Commands\DNM;

use App\Classes\LadaDNM\DNM;
use App\Models\DnmModel;
use App\Models\Mark;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DnmGetMark extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dnm:models';

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

        $models = $dnm->getModels();
        
        foreach($models as $item)
            $markNames[] = $item['name'];
        dump($markNames);
        $res = file_put_contents('/home/it/www/laravue/marks.txt', implode(PHP_EOL, $markNames));
        
        foreach($models as $item)
        {
            if($item['name'] == 'Niva')
                continue;
            if($item['name'] == 'Niva (4x4)')
                $item['name'] = 'Niva Legend';
            
            $marks = Mark::select('name','id')->where('brand_id', 1113)->where('name', 'like', '%'.$item['name'].'%')->get();
          
            $marks->each(function($myMark) use($item){
                
                if($myMark->id)
                    DnmModel::updateOrcreate(
                        ['mark_id' => $myMark->id,],
                        ['dnm_mark_id' => $item['id'],]
                    );
            });
        };
    }
}
