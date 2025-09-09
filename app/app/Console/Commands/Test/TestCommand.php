<?php

namespace App\Console\Commands\Test;

use App\Classes\Car\CarPriority\CarPriority;
use App\Classes\Car\CarPriority\PrioritySetter;
use App\Classes\Car\CarPriority\Test;
use App\Http\Filters\CarFilter;
use App\Models\Car;
use Illuminate\Console\Command;
use ZipArchive;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $host       = env('DB_HOST');
        $port       = env('DB_PORT');
        $db_name    = env('DB_DATABASE');
        $db_user    = env('DB_USERNAME');
        $db_pass    = env('DB_PASSWORD');
        $backup_name = 'SQL/backup-crm.sql';
        $single_trasaction = true;
        $zip_file = 'SQL/archive.zip';

        $command = [
            "mariadb-dump",
            $db_name,
            "--port={$port}",
            "--host={$host}",
            "--user={$db_user}",
            "--password={$db_pass}",
            $single_trasaction ? "--single-transaction" : "", 
            ">",
            $backup_name
        ];
        
        $back = exec(join(" ", $command), $output);

        if(empty($output))
        {
            dump('MYSQL DUMP: OK');

            $zip = new ZipArchive(); 
            $zip->open('SQL/archive.zip', ZIPARCHIVE::CREATE);
            $zip->addFile($backup_name);
            dump($zip->status);
            $zip->close();

            unlink($backup_name);

            $ftp = ftp_connect(env('BACKUP_FTP_HOST'), env('BACKUP_FTP_PORT'), 3600);

            ftp_login($ftp, env('BACKUP_FTP_USER'), env('BACKUP_FTP_PASS'));

            ftp_pasv($ftp, true);

            if(ftp_put($ftp, 'backup-crm.zip', $zip_file, FTP_BINARY))
            {
                dump('FTP LOAD: OK');
            }

            ftp_close($ftp);
        }
        else
        {
            dd('ERROR');
        }
    }
}
