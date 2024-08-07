<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisite extends Model
{
    use HasFactory;

    public $table = 'requisites';

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;
}
