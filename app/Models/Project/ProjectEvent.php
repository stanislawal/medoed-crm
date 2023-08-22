<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectEvent extends Model
{
    public $table = 'project_events';

    protected $fillable = [
        'project_id',
        'date',
        'comment'
    ];

    public $timestamps = true;

    protected $casts = [
        'date' => 'datetime:d.m.Y'
    ];
}
