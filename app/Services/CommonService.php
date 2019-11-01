<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2018/10/30
 * Time: 15:34
 */
namespace App\Services;

use App\Models\BankAccount;
use App\Models\BankInfo;
use App\Models\SettingBankAccount;
use App\Models\SettingCurrency;
use App\Models\SettingOption;
use App\Models\User;

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

    static public function getSMSPassword()
    {
        $rs=SettingOption::where('name','device_password')->first();
        $value=(isset($rs->value))?$rs->value :'';
        return $value;
    }
}