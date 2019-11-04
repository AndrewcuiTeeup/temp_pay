<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 */
namespace App\Services;


use App\Models\SMSBankTemplate;
use App\Models\SMSMessage;

class BankSMSService
{

    /**
     * @param $id
     * @param array $is_publish
     * @return mixed
     */
    static public function getById($id)
    {
        return SMSMessage::where('id','=',$id)->first();
    }

    /**
     * @param $data
     * @return mixed
     */
    static public function add($data)
    {
        return SMSMessage::create($data)->id;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    static public function updateById($id,$data)
    {
        return SMSMessage::where('id',$id)->update($data);
    }


    static public function listAll()
    {
        return SMSMessage::get();
    }

    static public function listAvailableBanksArray()
    {
        return SMSBankTemplate::pluck('bank_name')->toArray();
    }


}