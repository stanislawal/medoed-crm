<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mood extends Model
{
    public $table = 'moods';

    protected $fillable = [
        'name', //настроение
        'color' // цвет настроения
    ];

    public $timestamps = false;
}
