<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2019/5/13
 * Time: 12:24
 */

namespace App\Services;


use App\Models\Admin;
use Hash;
use Mail;

class AdminUserService
{

    /**
     * @param $id
     * @param array $is_publish
     * @return mixed
     */
    static public function getById($id)
    {
        return Admin::where('id','=',$id)->first();
    }

    /**
     * @param $data
     * @return mixed
     */
    static public function addUser($data)
    {
        return Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'type' => $data['type'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * @param $id
     * @param $data
     * @param null $email
     * @return mixed
     */
    static public function updateById($id,$data,$email=null)
    {
        if(empty($id) || empty($data))
        {
            return false;
         }
        Admin::where('id',$id)->update($data);
        if(!empty($email)){
            // send email to notice;
            $subject='Admin 账号有修改';
            $emailTemplate='emails.admin_change';
            $emailData=[];
            @Mail::send($emailTemplate,$emailData, function($message) use($email,$subject){
                $message->to($email)->subject($subject);
            });
        }
        return true;
    }

    /**
     * @param $id
     * @return mixed
     */
    static public function deleteById($id)
    {
        $mdlAdmin=Admin::find($id);
        $mdlAdmin->email.='_deleted';
        $mdlAdmin->password='deleted_'.time();
        $mdlAdmin->type='-1';
        $mdlAdmin->save();
    }

    static public function listAll()
    {
        return Admin::where('type','>',0)->get();
    }



}