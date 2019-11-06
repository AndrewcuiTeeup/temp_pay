<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = "shops";
    public $timestamps = false;
    protected $guarded = ['id'];
}
