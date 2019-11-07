<?php

namespace App\Http\Controllers\Admin;


use App\Models\SMSMessage;
use App\Services\DeviceService;
use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //
    public function deviceList()
    {
        $data['nav_title']= '设备管理';
        $data['data']=DeviceService::listAll();
        $data['settingOptions']=SettingService::listSettingOption();
        return view('admin.device.list')->with($data);
    }





}
