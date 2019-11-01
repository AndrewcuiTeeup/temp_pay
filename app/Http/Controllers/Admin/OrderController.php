<?php

namespace App\Http\Controllers\Admin;

use App\Services\AdminService;
use App\Services\MerchantApplicationService;
use App\Services\RechargeService;
use App\Services\UserApplicationService;
use App\Services\WithdrawService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $data=[];
        return view('admin.dashboard')->with($data);
    }


}

