<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPaymentProject extends Model
{
    public $table = 'status_payment_project';

    protected $fillable = [
        'name', // статус оплаты проекта
        'color' // цвет статуса
    ];

    public $timestamps = false;
}
