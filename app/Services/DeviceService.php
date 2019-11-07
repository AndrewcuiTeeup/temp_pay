<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 */
namespace App\Services;


use App\Models\Device;
use Illuminate\Support\Facades\DB;
use Mail;

class DeviceService
{

    /**
     * @param $id
     * @param array $is_publish
     * @return mixed
     */
    static public function getById($id)
    {
        return Device::where('id','=',$id)->first();
    }

    /**
     * @param $data
     * @return mixed
     */
    static public function add($data)
    {
        return Device::create($data)->id;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    static public function updateById($id,$data)
    {
        return Device::where('id',$id)->update($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    static public function deleteById($id)
    {
        return Device::where('id',$id)->delete();
    }

    static public function listAll()
    {
        return Device::get();
    }

    static public function getByName($name)
    {
        return Device::where('name','=',$name)->first();
    }

    static public function noticeOfflineDevices()
    {
        $rs= Device::where('is_online',0)->where('send_email_num','<',2)->get();
        if(count($rs)>0)
        {
            foreach ($rs as $val)
            {
                $email=$val->notice_email;
                $deviceName=$val->name;
                if(!empty($email)){
                    // send notice email
                    $emailTemplate='emails.device_offline';
                    $subject="-云闪付-设备 [{$deviceName}] 掉线了请查看 时间：".date('Y-m-d H:i:s');
                    $emailData=[];
                    @Mail::send($emailTemplate,$emailData, function($message) use($email,$subject){
                        $message->to($email)->subject($subject);
                    });
                }
            }
        }
        return true;
    }

    static public function autoCheckOnline()
    {
        $sql="UPDATE devices SET `is_online`=0,`send_email_num`=`send_email_num`+1  WHERE date_sub(updated_at,INTERVAL -5 MINUTE )<=Now() AND send_email_num<3";
         DB::update($sql);
        self::noticeOfflineDevices();
        return true;

    }

}