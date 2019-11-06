<?php

namespace App\Http\Controllers;
use App\Services\BankSMSService;
use App\Services\CommonService;
use App\Services\OrderService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use App\Services\DeviceService;
use Validator;

class SMSApiController extends Controller
{

    public function deviceStatus(Request $request)
    {
        // trim all input
        $input = array_map('trim', $request->all());
        // validate input data
        $rules = [
            'name'=>'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages(), 'status' => 'error'], 400);
        }

        $name=$input['name'];
        $rs=DeviceService::getByName($name);
        if(!empty($rs)){
            $id=$rs->id;
            $data=['is_online'=>1,'send_email_num'=>0,'updated_at'=>date('Y-m-d H:i:s',time())];
            DeviceService::updateById($id,$data);
        }
        return response()->json(['data' => '', 'status' => 'success'], 200);
    }

    public function message(Request $request)
    {
        $input = $request->all();
        // validate input data
        $rules = [
            'sign'=>'required',
            'send_data'=>'required',
            'batch_id'=>'required',
            'time'=>'required',
            'is_test'=>'nullable',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages(), 'status' => 'error'], 400);
        }

        /*$pwd=CommonService::getSMSPassword();
        $pwd=sha1($pwd);
        echo $pwd;*/
       // return response()->json(['batch_id'=>'123234','''=>'17日12:57账户*3141*汇款汇入收入162.20元，余额259.23元。对方户名:郑加宁（BERWFT）[兴业银行]','phone'=>'234343434']);
       // dd('-');
        $batch_id=$input['batch_id'];
        $sign=$input['sign'];
        $sendData=$input['send_data'];
        $message_time=$input['time'];

        if(empty($sendData)){
            return response()->json(['error' => 'invalid send data', 'status' => 'error'], 400);
        }

        if (self::checkSign($sign)===false) {
            return response()->json(['error' => 'invalid sign $sign='.$sign, 'status' => 'error'], 400);
        }
        if(!is_array($sendData)){
            return response()->json(['error' => 'invalid send data not array', 'status' => 'error'], 400);
        }
        if(count($sendData)>0){
            try {
                foreach ($sendData as $key => $val) {
                    if (!empty($val['msg'])) {
                        $strMsg = $val['msg'];
                        $rsMsg = self::checkMessage($strMsg);
                        if ($rsMsg === false) {
                            // 不符合条件的短信
                          /*  $data['phone'] = isset($val['phone']) ? $val['phone'] : '';
                            $data['message'] = $strMsg;
                            $data['batch_id'] = $batch_id;
                            $data['message_time'] = $message_time;
                            $data['status'] = '-1';
                            $id=BankSMSService::add($data); // save data*/
                            continue;
                        }
                        if ($rsMsg !== false) {
                            $data['phone'] = isset($val['phone']) ? $val['phone'] : '';
                            $data['message'] = $strMsg;
                            $data['bank'] = $rsMsg['bank'];
                            $data['batch_id'] = $batch_id;
                            $data['message_time'] = $message_time;
                            $data['amount'] = $rsMsg['amount'];
                            $id=BankSMSService::add($data); // save data
                            if(!empty($id))
                            {
                                $messageId=$id;
                                $amount=$data['amount'];
                                $rs=OrderService::smsConfirmRecharge($amount,$messageId,$rsMsg['bank']);
                            }
                        }

                    } else {
                        return response()->json(['data' => 'Invalid msg', 'status' => 'error'], 400);
                    }

                }
            }catch(\Exception $e)
            {
                return response()->json(['data' => $e->getMessage(), 'status' => 'error'], 400);
            }
            return response()->json(['data' => '', 'status' => 'success'], 200);
        }else{
            return response()->json(['data' => 'no data', 'status' => 'error'], 400);
        }

    }

    private function checkSign($str)
    {
        if(strlen($str)<=7){
            return false;
        }
        $pwd=CommonService::getSMSPassword();
        $pwd=sha1($pwd);
        $strPwd=substr($str,2);
        $strPwd=substr($strPwd,0,strlen($strPwd)-6);
        if($pwd===$strPwd)
        {
            return true;
        }
        return false;
    }

    private function checkMessage($str)
    {
        $activeBanks=SettingService::listActiveBanksAndTemplate();
        if(!empty($activeBanks)){
            foreach ($activeBanks as $row)
            {
                $bank_account=$row['bank_account'];
                $check_bank_account=substr($bank_account,-4);
                $bank_name=$row['bank_name'];
                $check_word_start=$row['check_word_start'];
                $check_word_end=$row['check_word_end'];

                // validate bank account
                $searchString=' '.$str;
                if(strpos($searchString,$check_bank_account)===false){
                    continue;
                }
                // check bank name
                if(strpos($searchString,$bank_name)===false){
                    continue;
                }
                // get amount
                $posStart=strpos($searchString,$check_word_start)+strlen($check_word_start);
                $posEnd=strpos($searchString,$check_word_end);
                $leng=(int)$posEnd-(int)$posStart;
                if($leng<=0){
                    continue;
                }
                $amount=substr($searchString,$posStart,$leng);

                if(!is_numeric($amount)){
                    continue;
                }
                if($amount>0){
                    return ['amount'=>$amount,'bank'=>$bank_name];
                    break;
                }

            }

        }

        return false;
    }

}
