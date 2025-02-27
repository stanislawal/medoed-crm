<?php

namespace App\Models\Lid;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallUp extends Model
{
    use HasFactory;

    public $table = 'call_ups';

    protected $fillable = [
        'name',
        'color'
    ];

    public $timestamps = true;
}
