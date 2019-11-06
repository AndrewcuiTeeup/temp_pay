<?php

namespace App\Http\Controllers;
use App\Models\BankInfo;
use App\Models\Order;
use App\Services\BankSMSService;
use App\Services\CommonService;
use App\Services\OrderService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Validator;

class PaymentController extends Controller
{

    public function generate(Request $request)
    {
        // trim all input
        $input = array_map('trim', $request->all());
        $rules = [
            'site_code' => 'required',
            'order_id' => 'required', //商户订单号
            'currency' => 'required',
            'amount' => 'required|numeric|min:0.01',
            'sign' => 'required',  //签名
            'redirect_url' => 'required|url',
            'notify_url' => 'nullable|url',
            'payee' => 'required',
            'currency_rate' => 'required|numeric',
            'paid_amount' => 'required|numeric|min:0.01',
            'comment' => 'filled',
            'debug' => 'filled',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            //if(isset($input['debug'])) {
                return response()->json(['error' => $validator->messages(),'code'=>4001, 'status' => 'error'], 400);
            //}
           // return response()->json(['error' => 'some params are missing or invalid format', 'code'=>4001, 'status' => 'error'], 400);
        } else {
            $shopCode=$input['site_code'];
            $orderId=$input['order_id'];
            $amount=$input['amount'];
            $sign=$input['sign'];
            $redirect_url=$input['redirect_url'];
            $payee=$input['payee'];
            $currency='CNY';
            $currency_rate=$input['currency_rate'];
            $paid_amount=round($input['paid_amount'],2);
            $rsShop=CommonService::getShopByCode($shopCode);

            if(empty($rsShop))
            {
                return response()->json(['error' =>'invalid shop code', 'code'=>4001,'status' => 'error'], 400);
            }
            $shopKey=md5($rsShop['secret_key']);
            if($this->checkSign(['site_code'=>$shopCode,'key'=>$shopKey,'orderId'=>$orderId,'amount'=>$amount],$sign)!=true)
            {
                return response()->json(['error' =>'invalid sign', 'code'=>4001,'status' => 'error'], 400);
            }
            // check paid amount
            if($paid_amount!=round($amount*$currency_rate,2)){
                return response()->json(['error' =>'invalid paid amount', 'code'=>4001,'status' => 'error'], 400);
            }

            //check bankinfo
            $rsBankInfo=CommonService::getAvailableBank([1]);

            if(empty($rsBankInfo)){
                return response()->json(['error' =>'invalid Bank info', 'code'=>4001,'status' => 'error'], 400);
            }
            // check and get final amount

            $final_amount=OrderService::createOrderCheckCode($paid_amount);
            if(empty($final_amount)){
                return response()->json(['error' =>'invalid final_amount', 'code'=>4001,'status' => 'error'], 400);
            }

            // save into database
            $data['refId']=uniqid(date('YmdHis'));
            $data['orderId']=$input['order_id'];
            $data['site_code']=$input['site_code'];
            $data['amount_usd']=$input['amount'];
            $data['payment_currency']=$currency;
            $data['currency_rate']=$currency_rate;
            $data['paid_amount']=$paid_amount;
            $data['final_amount']=$final_amount;
            $data['check_code']=$final_amount;
            $data['payee']=$payee;
            $data['payment_method']=1;
            $data['sign']=$sign;
            // bank info
            $data['bank_info_id']=$rsBankInfo->id;
            $data['bank_name']=$rsBankInfo->bank_name;
            $data['bank_account']=$rsBankInfo->bank_account;
            $data['bank_cardholder']=$rsBankInfo->bank_cardholder;

            if(isset($input['redirect_url'])){
                $data['redirect_url']=$input['redirect_url'];
            }
            if(isset($input['notify_url'])){
                $data['notify_url']=$input['notify_url'];
            }

            if(isset($input['comment'])){
                $data['comment']=$input['comment'];
            }

            $rs=OrderService::getOrderByOrderId($orderId,$shopCode);
            if(empty($rs)){
                OrderService::addOrder($data);
            }
            $id=base64_encode($orderId.'@'.$shopCode);
            //echo route('payment.order',$id); die();
            return redirect()->intended(route('payment.order',$id));

        }
    }

    protected function checkSign($attr,$sign)
    {
        if(is_array($attr)){
            $str=join($attr);
            $str=sha1($str);
            //  dd($str);
            if($str==$sign)
            {
                return true;
            }
        }
        return false;
    }

    public function test(Request $request)
    {
        //$rs=RechargeService::rechargePaidOrder(200,10,9);
        //dd($rs);
        $shopCode='GQ';
        $shopKey=md5('G565758788');
        $orderId='17';
        $amount='100';
        //$sign='dfdf';
        //shal1（site_code+MD5（secret_key）+orderId+amount）
        $sign=sha1($shopCode.$shopKey.$orderId.$amount);
        echo "?id=$orderId&amount=$amount&sign=$sign";
        $rs=  $this->checkSign(['site_code'=>$shopCode,'key'=>$shopKey,'orderId'=>$orderId,'amount'=>$amount],$sign);
        dd($rs);
        /* TradeService::resendAllNotify(0,1);
         die('dfdf');*/
        /* Auth::guard('admin')->logout();
         $request->session()->invalidate();
     die();
         $orderId='qww2';
         $rs=TradeService::getOrderByOrderId($orderId);
         $id=$rs['id'];
         dd(TradeService::sendNotify($id));*/

        /*    $notify_url=$rs['notify_url'];
            $param['order_id']=$rs['order_id'];
            $param['amount']=$rs['amount'];
            $param['sign']=$rs['sign'];
            $param['status']=$rs['status'];
            $res=APINotification::postRequest($notify_url,$param);
            $result=json_decode($res,true);
            if(isset($result['status']) && $result['status']=='success')
            {
                // stop resend
                TradeService::stopOrderResend($id);
            }else{
                TradeService::increaseOrderResend($id);
            }*/
    }


    public function show($id)
    {
        if(empty($id))
        {
            return response()->json(['error' =>'Invalid order 1', 'code'=>4001,'status' => 'error'], 400);
        }
        $idEncode=base64_decode($id);
        $arrayId=explode('@',$idEncode);
        if(count($arrayId)!=2)
        {
            return response()->json(['error' =>'Invalid order 2', 'code'=>4001,'status' => 'error'], 400);
        }
        $orderId=$arrayId[0];
        $siteCode=$arrayId[1];
        $rs=OrderService::getOrderByOrderId($orderId,$siteCode);
        if(empty($rs))
        {
            return response()->json(['error' =>'Invalid order 3', 'code'=>4001,'status' => 'error'], 400);
        }
        $data['order']=$rs;
        $data['bankInfo']=SettingService::getBank($rs->bank_info_id);
        $data['expired_url']=route('payment.order.expired',$id);
        $data['success_url']=route('payment.order.success',$id);
        return view('payment.order')->with($data);

    }

    public function orderStatus(Request $request)
    {
        // trim all input
        $input = array_map('trim', $request->all());
        $rules = [
            'id' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            //if(isset($input['debug'])) {
            return response()->json(['error' => $validator->messages(),'code'=>4001, 'status' => 'error'], 400);
            //}
            // return response()->json(['error' => 'some params are missing or invalid format', 'code'=>4001, 'status' => 'error'], 400);
        }else{
            $id=$input['id'];
            $rs=OrderService::getOrderById($id);
            if(empty($rs)){
                return response()->json(['error' => 'Invalid Order', 'code'=>4001, 'status' => 'error'], 400);
            }
            $data['id']=$id;
            $data['status']=$rs["status"];
            return response()->json(['data' => $data, 'status' => 'success'], 200);
        }

    }

    public function orderExpired($id)
    {
        if(empty($id))
        {
            return response()->json(['error' =>'Invalid order 1', 'code'=>4001,'status' => 'error'], 400);
        }
        $idEncode=base64_decode($id);
        $arrayId=explode('@',$idEncode);
        if(count($arrayId)!=2)
        {
            return response()->json(['error' =>'Invalid order 2', 'code'=>4001,'status' => 'error'], 400);
        }
        $orderId=$arrayId[0];
        $siteCode=$arrayId[1];
        $rs=OrderService::getOrderByOrderId($orderId,$siteCode);
        if(empty($rs))
        {
            return response()->json(['error' =>'Invalid order 3', 'code'=>4001,'status' => 'error'], 400);
        }
        $data['order']=$rs;
        return view('payment.status')->with($data);
    }

    public function orderSuccess($id)
    {
        if(empty($id))
        {
            return response()->json(['error' =>'Invalid order 1', 'code'=>4001,'status' => 'error'], 400);
        }
        $idEncode=base64_decode($id);
        $arrayId=explode('@',$idEncode);
        if(count($arrayId)!=2)
        {
            return response()->json(['error' =>'Invalid order 2', 'code'=>4001,'status' => 'error'], 400);
        }
        $orderId=$arrayId[0];
        $siteCode=$arrayId[1];
        $rs=OrderService::getOrderByOrderId($orderId,$siteCode);
        if(empty($rs))
        {
            return response()->json(['error' =>'Invalid order 3', 'code'=>4001,'status' => 'error'], 400);
        }
        $data['order']=$rs;
        return view('payment.status')->with($data);
    }

    public function notify(Request $request)
    {
        $input = array_map('trim', $request->all());
        $rules = [
            'id' => 'required'
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {

            return response()->json(['error' => 'ID required', 'code'=>401, 'status' => 'error'], 400);
        }
        $id=$input['id'];
        if(empty($id))
        {
            return response()->json(['error' =>'invalid order', 'code'=>401,'status' => 'error'], 400);
        }

        if(!empty($id)) {
            try {
                $res = OrderService::sendNotify($id);
            }catch(\Exception $e){
                return response()->json(['error' =>'invalid notice url', 'code'=>401,'status' => 'error'], 400);
            }
        }
        return response()->json(['status' => 'success'], 200);
    }
}
