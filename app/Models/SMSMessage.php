<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SMSMessage extends Model
{
    protected $table = "sms_bank_message";
    public $timestamps = false;
    protected $guarded = ['id'];
}
