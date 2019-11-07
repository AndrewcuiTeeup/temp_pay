<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 */
namespace App\Services;

use App\Models\Order;
use App\Models\SMSMessage;
use App\Notifications\InvoicePaid;
use DB;

class OrderService
{

    static public function addOrder($data)
    {
        if(empty($data)){
            return false;
        }
        $data['expire_time']=DB::raw("DATE_ADD(now(),interval 15 minute)");
        try{
           $rs=Order::create($data);
            return $rs->id;
        }catch (\Exception $e){
            return false;
        }

    }

    /**
     * @param $orderId
     * @param $site_code
     * @return mixed
     */
    static function getOrderByOrderId($orderId,$site_code)
    {
        return Order::where('orderId',$orderId)->where('site_code',$site_code)->first();
    }
    static public function smsConfirmRecharge($amount,$messageId,$bank_name)
    {
        if( empty($amount) || empty($messageId))
        {
            return false;
        }
        // validate
        $rs=Order::where('final_amount',$amount)->where('bank_name',$bank_name)->where('status',0)->first();
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

    static public function createOrderCheckCode($check_code)
    {

        $amountList= Order::where('final_amount','>=',intval($check_code))->where('final_amount','<',intval($check_code)+1)->where('status',0)->pluck('final_amount')->toArray();;
            if(count($amountList)<=0){
                $new_check_code=intval($check_code)+rand(0, 99)*0.01;
                return $new_check_code;
            }else{
                for($i=0;$i<10;$i++){
                    $new_check_code=intval($check_code)+rand(0, 99)*0.01;
                    if(!in_array($new_check_code,$amountList)){
                        return $new_check_code;
                        break;
                    }
                }
            }
        return false;
    }


    /**
     * @param $id
     * @return mixed
     */
    static public function stopOrderResend($id)
    {
        return Order::where('id',$id)->update(['allow_resend'=>1,'notify_resent_no'=>DB::raw('`notify_resent_no`+1')]);
    }

    /**
     * @param $id
     * @return mixed
     */
    static public function increaseOrderResend($id)
    {
        return Order::where('id',$id)->where('allow_resend',0)->update(['notify_resent_no'=>DB::raw('`notify_resent_no`+1')]);
    }

    /**
     * sendNotify  发送notify
     * @param $id
     * @return bool
     */
    static public function sendNotify($id)
    {
        $maxResend=10;
        $rs=self::getOrderById($id);
        if(empty($rs))
        {
            return false;
        }
        if(!empty(env('MAX_NOTIFY_NUM')))
        {
            $maxResend=env('MAX_NOTIFY_NUM');
        }
        if($rs['notify_resent_no']>$maxResend)
        {
            return false;
        }
        if(empty($rs['notify_url']))
        {
            return false;
        }
        $notify_url=$rs['notify_url'];
        $param['orderId']=$rs['orderId'];
        $param['refId']=$rs['refId'];
        $param['amount']=$rs['amount'];
        $param['sign']=$rs['sign'];
        $param['status']=$rs['status']==2 ?'success':'fail';
        $res=InvoicePaid::postRequest($notify_url,$param);
        $result=json_decode($res,true);
        if(is_array($result) && isset($result['status']) && $result['status']=='success')
        {
            // stop resend
            self::stopOrderResend($id);
        }else{
            self::increaseOrderResend($id);
        }
        return true;
    }


    /**
     * listResendNotify
     * @param int $skip
     * @param int $limit
     * @param string $orderField
     * @param string $asc
     * @return mixed
     */
    static public function listResendNotify($skip=0,$limit=100,$orderField='id',$asc='asc')
    {
        $maxResend=10;
        if(!empty(env('MAX_NOTIFY_NUM')))
        {
            $maxResend=env('MAX_NOTIFY_NUM');
        }
        return Order::where('allow_resend','=',0)
            ->where('status','=',2)
            ->where('notify_resent_no','<',$maxResend)
            ->whereNotNull('notify_url')
            ->orderBy($orderField,$asc)
            ->skip($skip)
            ->take($limit)
            ->get();
    }

    static public function resendAllNotify($start,$limit)
    {
        $rs=self::listResendNotify($start,$limit);
        if(!empty($rs)){
            $orderIdArray=[];
            foreach ($rs as $row)
            {
                $key=$row->id;
                $orderIdArray[$key]=$row->id;
            }
            if(!empty($orderIdArray))
            {

                // resend payment notice
                foreach($orderIdArray as $orderId) {
                    self::sendNotify($orderId);
                }

            }
        }
        return false;
    }




    static public function closeOrder()
    {
        $sql="UPDATE orders SET updated_at=NOW(),`status`=3,`check_code`=CONCAT_WS('-','expired',`id`)  WHERE DATE_SUB(created_at,INTERVAL -15 MINUTE )<=NOW() AND `status`=0";
        return DB::update($sql);
    }


}