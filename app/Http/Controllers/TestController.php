<?php

namespace App\Http\Controllers;

use App\Services\DeviceService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class TestController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //DeviceService::autoCheckOnline();
       // OrderService::closeOrder();
        OrderService::resendAllNotify(0,100);
    }
}
