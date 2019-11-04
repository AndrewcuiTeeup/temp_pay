<?php

namespace App\Http\Controllers\Admin;


use App\Services\BankSMSService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SMSMessage;
use Auth;


class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function bankList()
    {
        $data['selected_menu']= 'bankMenu';
        $data['data']=SettingService::listBanks();
        $data['template']=SettingService::listBankTemplate();
        $data['availableBank']=BankSMSService::listAvailableBanksArray();
        return view('admin.setting.bank_list')->with($data);
    }
    public function bankEditForm($id)
    {
        $data['data']=SettingService::getBank($id);
        $data['availableBank']=BankSMSService::listAvailableBanksArray();
        return view('admin.setting.bank_edit_form')->with($data);
    }

    public function bankStatus(Request $request)
    {
        // validate
        $rule=[
            'id'=>'required',
            'status'=>'required',
        ];
        $validData=$request->validate($rule);
        $id=$validData['id'];
        $status=$validData['status'];
        $rs=SettingService::updateBankStatus($id,$status);
        return response()->json(['data' => '', 'status' => 'success'], 200);
    }

    public function bankAdd(Request $request)
    {
        // validate
        $rule=[
            'bank_account'=>'required',
            'bank_name'=>'required',
            'bank_branch'=>'nullable',
            'bank_cardholder'=>'required',
            'status'=>'required',
            'qrcode'=>'required',
        ];
        $validData=$request->validate($rule);
        $data['bank_account']=$validData['bank_account'];
        $data['bank_name']=$validData['bank_name'];
        $data['bank_branch']=isset($validData['bank_branch'])? $validData['bank_branch']:'';
        $data['bank_cardholder']=$validData['bank_cardholder'];
        $data['qrcode']=$validData['qrcode'];
        $data['status']=$validData['status'];
        $rs=SettingService::addBank($data);
        if(isset($rs->id)) {
            return response()->json(['data' => '', 'status' => 'success'], 200);
        }else{
            return response()->json(['data' => '', 'status' => 'error'], 404);
        }
    }

    public function bankEdit(Request $request)
    {

        // validate
        $rule=[
            'id'=>'required',
            'bank_account'=>'required',
            'bank_branch'=>'nullable',
            'bank_name'=>'required',
            'bank_cardholder'=>'required',
            'status'=>'required',
            'qrcode'=>'required',
        ];
        $validData=$request->validate($rule);
        $data['bank_account']=$validData['bank_account'];
        $data['bank_name']=$validData['bank_name'];
        $data['bank_branch']=isset($validData['bank_branch'])? $validData['bank_branch']:'';
        $data['bank_cardholder']=$validData['bank_cardholder'];
        $data['status']=$validData['status'];
        $data['qrcode']=$validData['qrcode'];
        $id=$validData['id'];
        SettingService::updateBank($id,$data);
        return response()->json(['data' => '', 'status' => 'success'], 200);

    }

    public function smslog()
    {
        $data['nav_title']= '短信日志';
        return view('admin.setting.sms_log')->with($data);
    }

    public function sms_ajax_table(Request $request)
    {
        $columns = array(
            0 =>'id',
            1 =>'id',
            2=> 'id',
            3=> 'id',
            4=> 'id',
            5=> 'amount',
        );
        $draw=$request->input('draw');
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'id';
        $dir='desc';


        if($request->input('order.0.column')!=''){
            $order=isset($columns[$request->input('order.0.column')])?$columns[$request->input('order.0.column')]:$order;
        }
        if($request->input('order.0.dir')!=''){
            $dir=$request->input('order.0.dir');
        }
        if(empty($limit)){
            $limit=10;
        }

        //
        $sqlData=SMSMessage::where('id','>',0);


        $totalData = $sqlData->count();
        // search
        if(!empty($request->input('search.value')))
        {
            $searchJson=$request->input('search.value');
            $searchJson=json_decode($searchJson,true);
            if(isset($searchJson['search'])) {
                $search = $searchJson['search'];
                $type = $searchJson['type'];
                if (trim($search) != '') {
                    $searchVal = trim($search);
                    switch ($type) {
                        case 'id':
                            {
                                $sqlData->where('id', $searchVal);
                                break;
                            }
                        case 'batch_id':
                            {
                                $sqlData->where('batch_id', $searchVal);
                                break;
                            }
                        case 'check_code':
                            {
                                $sqlData->where('check_code', $searchVal);
                                break;
                            }
                        case 'amount':
                            {
                                $sqlData->where('amount', $searchVal);
                                break;
                            }
                    }

                }
                // from date
                $fromDate = $searchJson['fromDate'];
                if($fromDate!='') {
                    $sqlData->whereDate('message_time', '>=',$fromDate);
                }
                // to date
                $toDate = $searchJson['toDate'];
                if($toDate!='') {
                    $sqlData->whereDate('message_time','<=' ,$toDate);
                }

                $totalFiltered=$sqlData->count();
            }


        }else{
            // if no search
            $totalFiltered=$totalData;
        }

        $logs = $sqlData
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();
        $data=[];
        foreach($logs as $log) {
            switch ($log->status){
                case '0':{$log->status='Pending';break;}
                case '1':{$log->status='Success';break;}
            }
            $url='';
            $data[] = [
                'id' => $log->id,
                'message' => $log->message,
                'bank' => $log->bank,
                'batch_id' => $log->batch_id,
                'message_time' => $log->message_time,
                'phone' => $log->phone,
                'created_at' => $log->created_at,
                'check_code' => $log->check_code,
                'amount' => $log->amount,
                'status' => $log->status,
                'order_id' => $log->order_id,
                'url'=>$url,
            ];
        }
        $json_data = array(
            "draw"            => intval($draw),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        return response($json_data);
    }

}

