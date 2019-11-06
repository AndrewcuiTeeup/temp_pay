<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2018/10/30
 * Time: 15:34
 */
namespace App\Services;


use App\Models\BankInfo;
use App\Models\SettingOption;
use App\Models\Shop;


class CommonService
{

    /** get the CMS setting bank account list
     * @param array $status
     * @return mixed
     */
    static public function getBankList($status=[1])
    {
        $status=is_array($status) ? $status: [$status];
        return BankInfo::whereIn('status',$status)->get();
    }

    static public function getShopByCode($code)
    {
        return Shop::where('site_code',$code)->first();
    }

    static public function getSMSPassword()
    {
        $rs=SettingOption::where('name','device_password')->first();
        $value=(isset($rs->value))?$rs->value :'';
        return $value;
    }

    static public function getAvailableBank($status=[1])
    {
        $status=is_array($status) ? $status: [$status];

        $rs=BankInfo::whereIn('status',$status)->get();
        $num=count($rs);
        if($num<0){
            return false;
        }

        if($num==1){
            return $rs[0];
        }
        $randNum=rand(0,$num-1);
        return $rs[$randNum];
    }
}