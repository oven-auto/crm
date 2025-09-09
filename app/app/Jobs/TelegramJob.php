<?php

namespace App\Jobs;

use App\Classes\Telegram\Notice\TelegramNotice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TelegramJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    public $message;

    public $options;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $message, $options)
    {
        $this->user = $user;
        $this->message = $message;
        $this->options = $options;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        TelegramNotice::push($this->user, $this->message, $this->options);
    }
}
