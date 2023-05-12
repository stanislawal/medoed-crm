<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPayment extends Model
{
    public $table = 'status_payment';

    protected $fillable = [
        'name', // статус оплаты проекта
        'color' // цвет статуса
    ];

    public $timestamps = false;
}
