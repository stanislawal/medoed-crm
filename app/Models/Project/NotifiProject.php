<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;

class NotifiProject extends Model
{
    public $table = 'notifi_project';

    protected $fillable = [
        'project_id',
        'day'
    ];

    public $timestamps = false;
}
