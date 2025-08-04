<?php

namespace App\Models;

use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $table = 'statuses';

    protected $fillable = [
        'name', //статус проекта
        'color'
    ];

    public $timestamps = false;

    public function projects()
    {
        return $this->hasMany(Project::class, 'status_id');
    }
}
