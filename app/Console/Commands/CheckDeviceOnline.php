<?php

namespace App\Console\Commands;

use App\Services\DeviceService;
use Illuminate\Console\Command;

class CheckDeviceOnline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkDeviceOnline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check device if is online';

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
        DeviceService::autoCheckOnline();
    }
}
