<?php

namespace App\Console\Commands;

use App\Classes\LadaDNM\DNM;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CME_TEST_MARK extends Command
{
    private static $token;
    private const URL_MODEL = 'https://appraisal.api.cm.expert/v1/autocatalog/models';

    public function __construct()
    {
        parent::__construct();
        self::$token = \App\Classes\SMExpert\Token::getInstance()->getToken();
    }

    

    protected $signature = 'cme:test';


    
    protected $description = 'Command description';



    public function getPattern(int $count, $val = ' ') : string
    {
        $str = '';
        
        for($i = 0; $i < $count; $i++)
            $str .= $val;
        
        return $str;
    }

    
    public function handle()
    {
        $response = Http::withHeaders([
            'Authorization' => self::$token
        ])->get(self::URL_MODEL, ['brand' => 87]);

        $models = $response['models'];

        $this->  info("CME");      
    
        foreach($models as $itemModel)
        {
            $pattern = $this->getPattern(10);
            
            $id = mb_substr($pattern, 0, -strlen($itemModel['id'])).$itemModel['id'];

            $this->line(implode(' => ', [$id, $itemModel['text']]));
        }

        $this->info("DNM");
        
        $dnm = DNM::init();

        $dnmMOdels = $dnm->getModels();

        foreach($dnmMOdels as $itemModel)
        {
            $pattern = $this->getPattern(10);
            
            $id = mb_substr($pattern, 0, -strlen($itemModel['id'])).$itemModel['id'];

            $this->line(implode(' => ', [$id, $itemModel['name']]));
        }
    }
}
