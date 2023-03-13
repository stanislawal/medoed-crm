<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    public $table = 'currencies';

    protected $fillable = [
        'currency' //Валюта
    ];
    public $timestamps = false;
}
