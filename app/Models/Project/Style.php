<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Style extends Model
{
    public $table = 'styles';

    protected $fillable = [
        'name' //стиль написанного текста
    ];

    public $timestamps = false;
}
