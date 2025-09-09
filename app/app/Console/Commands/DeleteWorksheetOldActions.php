<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteWorksheetOldActions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:old:action';

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
        $actualActions = \App\Models\WorksheetAction::select(\DB::raw('max(worksheet_actions.id) as id'))
            ->groupBy('worksheet_actions.worksheet_id')
            ->havingRaw('COUNT(worksheet_actions.id) > ?', [1])
            ->pluck('id');

        if(!$actualActions->count()){
            print( "#########################################################\n" );
            print( "# Старых действий РЛ не найдено, действия РЛ не засраны #\n" );
            print( "#########################################################\n" );
            return 1;
        }

        \App\Models\WorksheetAction::whereNotIn('worksheet_actions.id', $actualActions->toArray())->delete();
        print( "#########################################################\n" );
        print ("############### Старые события РЛ удалены ###############\n");
        print( "#########################################################\n" );
        print( "\n");
        print( "Актуальные события: ".implode(',', $actualActions->toArray()) ."\n" );
    }
}
