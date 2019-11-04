<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SMSBankTemplate extends Model
{
    protected $table = "sms_bank_template";
    public $timestamps = false;
    protected $guarded = ['id'];
}
