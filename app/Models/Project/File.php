<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public $table = 'files';

    protected $fillable = [
        'project_id',
        'url'
    ];

    public $timestamps = true;
}
