<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 */
namespace App\Services;



use App\Models\Order;
use App\Models\SMSMessage;

class OrderService
{


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
        // update sms
        SMSMessage::where('id',$messageId)->update(['order_id'=>$id,'status'=>1]);

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

    static public function getOrderById($id)
    {
        return Order::where('id',$id)->first();
    }
}