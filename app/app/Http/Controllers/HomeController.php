<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

use App\Classes\LadaDNM\DNM;

use App\Classes\LadaDNM\Services\DNMEvent as ServicesDNMEvent;
use App\Classes\LadaDNM\Services\DNMVehicleService;
use App\Classes\LadaDNM\Services\NewDNMClientService as ServicesNewDNMClientService;
use App\Classes\LadaDNM\Services\NewDNMReserveService as ServicesNewDNMReserveService;
use App\Classes\LadaDNM\Services\NewDNMWorksheetService as ServicesNewDNMWorksheetService;
use App\Classes\ORM\ORMConnection;
use App\Classes\Vin\Vin;
use App\Events\CreateServiceClientEvent;
use App\Events\DNMVisitEvent;
use App\Events\ReserveCreateEvent;
use App\Helpers\String\StringHelper;
use App\Http\Requests\For1C\ClientFindRequest;
use App\Jobs\CreateDNMReserveJob;
use App\Jobs\TestJob;
use App\Listeners\DNMReserveCreateListener;
use App\Models\Audit\AuditRecord as AuditAuditRecord;
use App\Models\AuditRecord;
use App\Models\Car;
use App\Models\Client;
use App\Models\ClientCar;
use App\Models\ClientFile;
use App\Models\ClientUnion;
use App\Models\DealerColorImage;
use App\Models\DNMBrand;
use App\Models\MarkAlias;
use App\Models\Trafic;
use App\Models\DnmClient as ModelsDnmClient;
use App\Models\TraficProduct;
use App\Models\Tuning;
use App\Models\User;
use App\Models\Worksheet;
use App\Models\Worksheet\Service\WSMService;
use App\Models\WsmReserveNewCar;
use App\Repositories\Car\Car\CarRepository;
use App\Repositories\Trafic\TraficRepository;
use App\Repositories\Worksheet\Modules\Reserve\ReserveRepository;
use App\Repositories\Worksheet\WorksheetRepository;
use App\Services\Car\CalculatePaidDate;
use App\Services\Client\ClientEvent\ClientEventService;
use Carbon\Carbon;
use GuzzleHttp\RequestOptions;
use Icewind\SMB\BasicAuth;
use Icewind\SMB\ServerFactory;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use ReflectionClass;
use SplQueue;
use SplStack;
use stdClass;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
use ZipArchive;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}



    /**
     * ПОЛУЧИТЬ СОСТОЯНИЕ ВЕБХУКА ТЕЛЕГРАМ
     */
    public function get()
    {
        $token = env('TELEGRAM_KEY');

        $telegram = new Api($token);

        $res = $telegram->getWebhookInfo();

        dump($res);
    }



    /**
     * УСТАНОВИТЬ ВЕБХУК ТЕЛЕГРАМ
     */
    public function set()
    {
        $token = env('TELEGRAM_KEY');

        $url = 'https://62.182.31.140/telegram/bot';

        $command = 'curl -k -F "url=' . $url . '" -F "certificate=@cert/cert2.pem" "https://api.telegram.org/bot' . $token . '/setWebhook"';

        exec($command, $out, $res);

        dump($out);
    }



    /**
     * УДАЛИТЬ ВЕБХУК ТЕЛЕГРАМ
     */
    public function del()
    {
        // $token = env('TELEGRAM_KEY');

        // $telegram = new Api($token);

        // $res = $telegram->removeWebhook();
    }



    /**
     * БОТ ТЕЛЕГРАМ
     */
    public function bot(Request $request) {}



    private function priority(string $key) : int
    {
        return match($key){
            '(' => 0,
            ')' => 0,
            '+' => 1,
            '-' => 1,
            '*' => 2,
            '/' => 2,
            '^' => 3,
            '~' => 4,
            'default' => 0,
        };
    }



    public function isOperand(string $key) : bool
    {
        $operand = ['+', '-', '*', '/', '^', '~', '(', ')'];

        return in_array($key, $operand) ? 1 : 0;
    }



    public function isUnarMinus(string $key)
    {
        return $key == '~' ? 1 : 0;
    }



    public function toInfix(string $data) : SplQueue
    {
        $queue = new SplQueue();
        $tmpVal = '';
        
        for($i = 0; $i < mb_strlen($data); $i++)
        {
            if($this->isOperand($data[$i]))
            {
                if($tmpVal)
                    $queue->push($tmpVal);

                
                if(($queue->count() == 0 || $this->isOperand($data[$i-1])) && $data[$i] == '-')
                {
                    $queue->push(0);
                    $queue->push('-');
                    continue;
                }


                $queue->push($data[$i]);
                $tmpVal = '';
            }
            else
            {
                $tmpVal .= $data[$i];
            }

            if($i == mb_strlen($data)-1)
                if($tmpVal)
                    $queue->push($tmpVal);
        }

        return $queue;
    }



    public function postFix(SplQueue $data) : SplQueue
    {
        $currentPriority    = 0;
        $priority           = 0;
        $stackString        = new SplQueue();
        $stackOper          = new SplQueue();
        
        while($data->count())
        {   
            if(!$this->isOperand($data->bottom()))
                $stackString->push($data->shift());

            else
            {
                $currentPriority = $this->priority($data->bottom());                

                if($data->bottom() == ')')
                {
                    while($stackOper->count())
                    {
                        if($stackOper->top() == '(')
                            break;
                        $stackString->push($stackOper->pop());
                    }
                    dump($stackOper);
                    $priority = $currentPriority;
                    continue;
                }

                // if($currentPriority <= $priority)
                // {
                //     while($stackOper->count())
                //     {
                //         if($stackOper->top() == '(')
                //             break;
                //         $stackString->push($stackOper->pop());
                //     }
                // }

                $stackOper->push($data->shift());

                $priority = $currentPriority;
            }

            if($data->count()==0)
                while($stackOper->count())  
                    $stackString->push($stackOper->pop());
        }

        return $stackString;
    }



    public function printStack($stack)
    {
        $res = '';
        foreach($stack as $item)
            $res .= $item;
        dump($res);
    }



    public function calculate(SplQueue $queue) : int|float
    {
        $valStack = new SplStack();
        $y = 0;
        $x = 0;
        
        foreach($queue as $item)
        {
            if($item)
                if(!$this->isOperand($item))
                {
                    $valStack->push($item);
                }
                else
                {   
                    if($this->isUnarMinus($item))
                    {
                        $y = 0;
                        $x = $valStack->pop();
                    }
                    else
                    {
                        $y = $valStack->pop();
                        $x = $valStack->pop();
                    }

                    
                    dump($x . ' & '.$y);
                    $valStack->push(match($item){
                        '+' => $this->summation($x, $y),
                        '-' => $this->subtraction($x, $y),
                        '~' => $this->subtraction($x, $y),
                        '*' => $this->multiplication($x, $y),
                        '/' => $this->division($x, $y),
                        '^' => $this->exponentiation($x, $y),
                        'default' => throw new \Exception('Error'),
                    });
                }
        }

        return $valStack->top();
    }



    public function summation(int|float $x, int|float $y)  : int|float
    {
        return $x + $y;
    }



    public function subtraction(int|float $x, int|float $y) : int|float
    {
        return $x - $y;
    }



    public function multiplication(int|float $x, int|float $y) : int|float
    {
        return $x * $y;
    }



    public function division(int|float $x, int|float $y) : int|float
    {
        return $x / $y;
    }



    public function exponentiation(int|float $x, int|float $y) : int|float
    {
        return pow($x, $y);
    }



    public function index(Request $request) 
    {
        
    }











    public function scan(ZipArchive $zip, string $path)
    {
        $dir = opendir($path);

        while($file = readdir($dir))
        {
            $current = $path.'/'.$file;

            if($file == '.' || $file == '..')
                continue;

            if(is_file($current))
                $zip->addFile($current, $current);
            elseif(is_dir($current))
                $this->scan($zip, $path.'/'.$file);
        }
    }



    public function puz(array $arr)
    {
        $count = count($arr);
        $count_itr = $count - 1;

        $masP = $arr;
        $t = 0;

        $time_start = microtime(true);
        for($i = 0; $i < $count; $i++)
            for($k = 0; $k < ($count_itr-$i); $k++)
            {
                if($masP[$k] > $masP[$k + 1])
                    list($masP[$k], $masP[$k+1]) = [$masP[$k+1], $masP[$k]];
                $t++;
            }
        $time_end = microtime(true);

        $execution_time = ($time_end - $time_start);

        return([
            'name' => 'Buble',
            'time' => $execution_time,
            'string' => implode(',',$masP),
            'iteration' => $t,
        ]);
    }



    public function coctail(array $arr)
    {
        $masS = $arr;
        
        $begin = 0;
        $end = count($masS)-1;
        $t = 0;   
        $time_start = microtime(true);
        while($end > $begin)
        {
            for($i=$begin; $i<$end; $i++)
            {
                $t++;
                if($masS[$i] > $masS[$i+1])
                    list($masS[$i], $masS[$i+1]) = [$masS[$i+1], $masS[$i]];
            }
            $end--;

            for($i=$end; $i>$begin; $i--)
            {
                $t++;
                if($masS[$i] < $masS[$i-1])
                    list($masS[$i], $masS[$i-1]) = [$masS[$i-1], $masS[$i]];
            }
            $begin++;   
        }

        $time_end = microtime(true);

        $execution_time = ($time_end - $time_start);
        return([
            'name' => 'Coctail',
            'time' => $execution_time,
            'string' => implode(',',$masS),
            'iteration' => $t,
        ]);
    }



    public function pastle(array $mas)
    {
        $arr = $mas;
        $n = count($arr); 
        $t = 0;
        
        $time_start = microtime(true);
        for ($i = 1; $i < $n; $i++)
        { 
            $j = $i-1; 
            $key = $arr[$i]; 
            while ($j >= 0 && $arr[$j] > $key)
            { 
                $t++;
                $arr[$j+1] = $arr[$j]; 
                $j--; 
            } 
            $arr[$j+1] = $key; 
        } 
        $time_end= microtime(true);
        
        $execution_time = ($time_end - $time_start);
        return([
            'name' => 'Insert',
            'time' => $execution_time,
            'string' => implode(',',$arr),
            'iteration' => $t,
        ]);
    }




    public function makeHeapTree(array &$arr, int $count, int $index, int &$t)
    {
        //корень
        $root = $index;
        // левыйРебенок = 2*i + 1
        $leftChild      = 2*$index + 1; 
        // правыйРебенок = 2*i + 2
        $rightChild     = 2*$index + 2;

        // Если левый дочерний элемент больше корня и не вышли за пределы массива
        if ($leftChild < $count && $arr[$leftChild] > $arr[$root])
            $root = $leftChild;

        //Если правый дочерний элемент больше корня и не вышли за пределы массива
        if ($rightChild < $count && $arr[$rightChild] > $arr[$root])
            $root = $rightChild;

        // Если самый большой не корень
        if ($root != $index)
        {
            list($arr[$root], $arr[$index]) = [$arr[$index], $arr[$root]];
            $t++;
            //берем его как корень
            $this->makeHeapTree($arr, $count, $root, $t);
        }
    }


        
    public function heapSort(array &$arr)
    {
        $count = count($arr);
        $t = 0;
        
        $time_start = microtime(true);
        for($i = intdiv($count, 2)-1; $i >= 0; $i--)
        {
            $t++;
            $this->makeHeapTree($arr, $count, $i,$t);
        }
    
        for($i = $count-1; $i > 0; $i--)
        {
            list($arr[0], $arr[$i]) = [$arr[$i], $arr[0]];
            $t++;
            $this->makeHeapTree($arr, $i, 0, $t);
        }
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);

        return([
            'name' => 'Heap',
            'time' => $execution_time,
            'string' => implode(',',$arr),
            'iteration' => $t,
        ]);
    }



    public function printRes(array $res)
    {
        dump([
            'name' => $res['name'],
            'time' => $res['time'],
            'iteration' => $res['iteration'],
            'string' => $res['string']
        ]);
    }



    public function test(Request $request)
    {

    }



    public function test12(WorksheetRepository $repo, TraficRepository $traficRepo)
    {
        
    }




    public function test1($id = 0)
    {

    }

    

    public function rr()
    {
        $str = '1+2*5';
        $length = strlen($str);
        $val = '';
        $mas = [];

        for ($i = 0; $i < $length; $i++) {

            if (isset($str[$i])) {
                if (is_numeric($str[$i])) {
                    $val .= $str[$i];
                } else {
                    if ($val)
                        $mas[] = $val;
                    $mas[] = $str[$i];
                    $val = '';
                }

                if ($i == $length - 1 && $val) {
                    $mas[] = $val;
                }
            }
        }

        $valStack = [];
        $keyStack = [];
        foreach ($mas as $item) {
            if (is_numeric($item))
                array_push($valStack, $item);
            else {
                if (count($keyStack) == 0)
                    array_push($keyStack, $item);
                else {
                    if ($this->isSymbol($item)) {
                        $currentPriority = $this->getPriority($item);
                        foreach ($keyStack as $key)
                            if ($currentPriority >= $this->getPriority($key))
                                $valStack[] = 1;
                    }
                }
            }
        }
    }


    public function isSymbol($val)
    {
        $mas = ['+', '-', '*', '/'];

        if (array_search($val, $mas))
            return 1;
        return 0;
    }


    public function getPriority($key)
    {
        $priorityMas = [
            '(' => 0,
            ')' => 1,
            '+' => 2,
            '-' => 2,
            '*' => 3,
            '/' => 3,
        ];

        $keyPriority = $priorityMas[$key];

        return $keyPriority;
    }



    private function isAdditionOrSubtraction($val)
    {
        return ($val == '+' || $val == '-') ? 1 : 0;
    }



    public function getCMEDealerID()
    {
        $token = \App\Classes\SMExpert\Token::getInstance()->getToken();

        $url = 'https://lk.cm.expert/api/v1/dealers';

        $response = Http::withHeaders([
            'Authorization' => $token
        ])->get($url);

        dd($response->json());
    }

    public function addYear($date, $year = 1)
    {
        return date('Y-m-d H:i:s', strtotime("+$year year", strtotime($date)));
    }

    public function addMonth($date, $month = 1)
    {
        return date('Y-m-d H:i:s', strtotime("+$month month", strtotime($date)));
    }

    public function addDay($date, $day = 1)
    {
        return date('Y-m-d H:i:s', strtotime("+$day day", strtotime($date)));
    }

    public function addWeek($date, $week = 1)
    {
        return date('Y-m-d H:i:s', strtotime("+$week week", strtotime($date)));
    }
}
