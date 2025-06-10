<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrossServiceSpecialist extends Model
{
    use HasFactory;

    protected $table = 'cross_service_specialists';

    protected $fillable = [
        'service_id',
        'specialist_service_id'
    ];
}
