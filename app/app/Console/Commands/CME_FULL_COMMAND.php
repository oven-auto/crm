<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CME_FULL_COMMAND extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cme:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync SMExpertApi';

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
        $this->call('cme:body');
        $this->call('cme:gear');
        $this->call('cme:driver');
        $this->call('cme:engine');
        $this->call('cme:color');
        $this->call('cme:brand');
    }
}
