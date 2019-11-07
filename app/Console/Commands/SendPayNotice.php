<?php

namespace App\Console\Commands;

use App\Services\OrderService;
use Illuminate\Console\Command;

class SendPayNotice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paymentNotice {start} {limit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'push data to payment notify url';

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
     * @return mixed
     */
    public function handle()
    {
        //
        $start = $this->argument('start');
        $limit = $this->argument('limit');
        $limit=!empty($limit)?$limit:10;
        OrderService::resendAllNotify($start,$limit);
    }
}
