<?php

namespace App\Models\Lid;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    public $table = 'audits';

    protected $fillable = [
        'name',
        'color'
    ];

    public $timestamps = true;
}
