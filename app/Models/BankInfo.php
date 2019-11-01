<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankInfo extends Model
{
    protected $table = "bank_info";
    public $timestamps = false;
    protected $guarded = ['id'];
}
