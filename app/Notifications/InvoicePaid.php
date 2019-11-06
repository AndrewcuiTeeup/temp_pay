<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InvoicePaid extends Notification
{
    public static function postRequest($url, $params,$authorization=null)
    {
        // Get cURL resource
        $curl = curl_init($url);
        $params=json_encode($params);
        $arrayHeader=array('Content-Type: application/json; charset=utf-8', 'Content-Length:' . strlen($params));

        if(!empty($authorization))
        {
            array_push($arrayHeader,'Authorization: '.$authorization);
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $arrayHeader);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_TIMEOUT,10); // 最大时间 10 s

        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
        // Return response
        return $resp;
    }
}
