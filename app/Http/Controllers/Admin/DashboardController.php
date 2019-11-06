<?php

namespace App\Http\Controllers\Admin;

use App\Services\AdminService;
use App\Services\MerchantApplicationService;
use App\Services\OrderService;
use App\Services\RechargeService;
use App\Services\SettingService;
use App\Services\UserApplicationService;
use App\Services\WithdrawService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $data=[];
        $today=date('Y-m-d',time());
        $data['today_orders']=OrderService::listTodaySuccessOrder($today);
        $data['today_balance_USD']=OrderService::successBalanceUSD($today);
        $data['today_balance_CNY']=OrderService::successBalanceCNY($today);
        $data['balance_USD']=OrderService::successBalanceUSD();
        $data['balance_CNY']=OrderService::successBalanceCNY();
        $data['shop']=SettingService::getShopById(1);
        return view('admin.dashboard')->with($data);
    }


}

