<?php

namespace App\Models;

use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $table = 'notifications';

    protected $fillable = [
        'date_time',
        'type',
        'recipient_id',
        'message',
        'project_id',
        'article_id',
        'is_viewed',
    ];
    public $timestamps = false;

    protected $casts = [
        'date_time' => 'datetime:Y-m-d H:i'
    ];

    public function projects() {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function articles() {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
