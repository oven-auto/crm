<?php

namespace App\Console\Commands\Socket;

use Illuminate\Console\Command;
use Workerman\Worker;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

class SocketServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:run {action} {--daemonize}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $httpWorker = new Worker('http://0.0.0.0:2244');

        $httpWorker->count = 4;

        $httpWorker->onMessage = function($connect, $request)
        {
            if (file_exists(__DIR__.'/../../../../storage/framework/maintenance.php')) {
                require __DIR__.'/../../../../storage/framework/maintenance.php';
            }
            require __DIR__.'/../../../../vendor/autoload.php';
            //echo __DIR__.'/../../../../vendor/autoload.php';
            //echo PHP_EOL.__DIR__.'../../../../vendor/autoload.php'.PHP_EOL;

            //$app = require_once __DIR__.'/../../../../bootstrap/app.php';

            //$kernel = $app->make(Kernel::class);

            // $response = tap($kernel->handle(
            //     $request = Request::capture()
            // ))->send();

            $connect->send(1);
        };

        Worker::runAll();
    }
}
