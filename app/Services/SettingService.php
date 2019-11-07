<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 */
namespace App\Services;


use App\Models\BankInfo;
use App\Models\SettingOption;
use App\Models\Shop;
use App\Models\SMSBankTemplate;
use DB;

class SettingService
{

    /**
     * Update  setting bank account status
     * @param $id
     * @param $status
     * @return mixed
     */
    static public function updateBankStatus($id,$status)
    {
        return BankInfo::where('id',$id)->update(['status'=>$status]);
    }

    /** Add record to setting_bank_account
     * @param $data
     * @return mixed
     */
    static public function addBank($data)
    {
        return BankInfo::create($data);
    }

    static public function updateBank($id,$data)
    {
        return BankInfo::where('id',$id)->update($data);
    }

    static public function getBank($id,$status=[0,1])
    {
        return BankInfo::where('id',$id)->whereIn('status',$status)->first();
    }

    static public function listBanks($status=[0,1])
    {
        return BankInfo::whereIn('status',$status)->get();
    }

    static public function listActiveBanksAndTemplate()
    {
        return BankInfo::join('sms_bank_template','sms_bank_template.bank_name','=','bank_info.bank_name')
                        ->where('bank_info.status',1)
                        ->select('bank_info.bank_account','sms_bank_template.*')
                        ->get();
    }

    static public function listBankTemplate($status=[0,1])
    {
        return SMSBankTemplate::whereIn('status',$status)->get();
    }

    static public function getBankTemplate($id)
    {
        return SMSBankTemplate::where('id',$id)->first();
    }

    static public function updateBankTemplate($id,$data)
    {
        return SMSBankTemplate::where('id',$id)->update($data);
    }

    static public function updateShop($id,$data)
    {
        return Shop::where('id',$id)->update($data);
    }
    static public function getShopById($id)
    {
        return Shop::where('id',$id)->first();
    }

    static public function listSettingOption()
    {
        return SettingOption::get();
    }

}