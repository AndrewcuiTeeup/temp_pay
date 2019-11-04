<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Services\AdminService;
use App\Services\BankSMSService;
use App\Services\MerchantApplicationService;
use App\Services\OrderService;
use App\Services\RechargeService;
use App\Services\UserApplicationService;
use App\Services\WithdrawService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $data=[];
        $data["orders"]=[];
        return view('admin.order.list')->with($data);
    }

    public function ajax_log_table(Request $request)
    {
        $columns = array(
            0 =>'id',
            4=> 'amount',
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


        $sqlData=Order::select('orders.*');


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
                        case 'refID':
                            {
                                $sqlData->where('refId', $searchVal);
                                break;
                            }
                        case 'name':
                            {
                                $sqlData->where('payee', $searchVal);
                                break;
                            }
                        case 'amount':
                            {
                                $sqlData->where('final_amount', $searchVal);
                                break;
                            }
                    }

                }
                // from date
                $fromDate = $searchJson['fromDate'];
                if($fromDate!='') {
                    $sqlData->whereDate('updated_at', '>=',$fromDate);
                }
                // to date
                $toDate = $searchJson['toDate'];
                if($toDate!='') {
                    $sqlData->whereDate('updated_at','<=' ,$toDate);
                }

                // status
                $status = $searchJson['status'];
                if((int)$status>=0) {
                    $sqlData->where('status',$status);
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
            $statusArray=['0'=>'Pending','1'=>'Success','2'=>'Cancelled','3'=>'Expired'];
            $link_view='<a href="javascript:void(0)" onclick="commonViewDialog(\'' .route('admin.order.detail',['id'=>$log->id]).'\',\'View\')">View</a>';
            $data[] = [
                'id' => $log->id,
                'refID' => $log->refId,
                'amount_usd' => $log->amount_usd,
                'payee' => $log->payee,
                'payment_currency' => $log->payment_currency,
                'final_amount' => $log->payment_currency.' '.$log->final_amount,
                'updated_at' => $log->updated_at,
                'bank_name' => $log->bank_name,
                'bank_account' => $log->bank_account,
                'status' => isset($log->status) ? $statusArray[$log->status] :'',
                'sms_bank_message_id' => $log->sms_bank_message_id,
                'expire_time' => $log->expire_time,
                'control'=>$link_view
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

    public function detail($id)
    {
        $rs=OrderService::getOrderById($id);
        if(!empty($rs)){
            $data['data']=$rs;
            $data["sms_bank_message_content"]='--';
            if(!empty($rs->sms_bank_message_id))
            {
                $rsSMS=BankSMSService::getById($rs->sms_bank_message_id);

                if(!empty($rsSMS['message']))
                {
                    $data["sms_bank_message_content"]=$rsSMS['message'];
                }
            }

            return view('admin.order.detail')->with($data);
        }else{
            echo 'Invalid Request！！！';
        }

    }


}

