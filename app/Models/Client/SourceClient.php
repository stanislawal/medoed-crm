<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourceClient extends Model
{
    use HasFactory;

    public $table = 'source_clients';

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;
}
