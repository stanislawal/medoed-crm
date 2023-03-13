<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialNetwork extends Model
{
    public $table = 'social_networks';

    protected $fillable = [
        'name' //название социальной сети
    ];

    public $timestamps = false;
}
