<?php

namespace App\Models\Rate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    public $table = 'rates';

    protected $fillable = [
        'id_currency', //id валюты
        'rate' //курс
    ];

    public $timestamps = false;
}
