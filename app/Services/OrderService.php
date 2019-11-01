<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 */
namespace App\Services;


use App\Models\Device;
use App\Models\Order;
use App\Models\SMSMessage;

class OrderService
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

    static public function smsConfirmRecharge($amount,$messageId)
    {
        if( empty($amount) || empty($messageId))
        {
            return false;
        }
        // validate
        $rs=Order::where('final_amount',$amount)->where('status',0)->first();
        if(empty($rs))
        {
            return false;
        }
        if(empty($rs->id))
        {
            return false;
        }
        $id=$rs->id;
        // update order
        $data['status']=1;
        $data['check_code']='success-'.$rs->id;
        $data['sms_bank_message_id']=$messageId;
        $data['updated_at']=date('Y-m-d h:i:s',time());
        Order::where('id',$id)->update($data);
        // update

        return true;
    }


    static public function listTodaySuccessOrder($date)
    {
        return Order::where('status',1)->whereDate('updated_at',$date)->get();

    }

    static public function successBalanceUSD($date=null)
    {
        if(!empty($date)){
            return Order::where('status',1)->whereDate('updated_at',$date)->sum('amount_usd');
        }
        return Order::where('status',1)->sum('amount_usd');

    }

    static public function successBalanceCNY($date=null)
    {
        if(!empty($date)){
            return Order::where('status',1)->where('payment_currency','CNY')->whereDate('updated_at',$date)->sum('final_amount');
        }
        return Order::where('status',1)->where('payment_currency','CNY')->sum('final_amount');
    }
}