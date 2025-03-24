<?php

namespace App\Models;

use App\Models\Lid\Lid;
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
        'user_id', // запись пользователя, который обновил статус в базе лида
        'lid_id',
        'is_viewed'
    ];
    public $timestamps = false;

    protected $casts = [
        'date_time' => 'datetime:d.m.Y H:i'
    ];

    public function projects() {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function articles() {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function lid()
    {
        return $this->belongsTo(Lid::class, 'lid_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
