<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public $table = 'files';

    protected $fillable = [
        'project_id',
        'client_id',
        'url',
        'file_name',
        'comment'
    ];

    public $timestamps = true;
}
