<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    public $table = 'themes';

    protected $fillable = [
        'name' //тема проекта
    ];

    public $timestamps = false;

    public function projects()
    {
        return $this->hasMany(Project::class, 'theme_id');
    }
}
