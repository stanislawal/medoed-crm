<?php

namespace App\Models;

use App\Models\Project\Project;
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

    public function projects()
    {
        return $this->hasMany(Project::class, 'status_payment_id');
    }
}
