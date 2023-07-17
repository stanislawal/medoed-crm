<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActive extends Model
{
    public $table = 'user_active';

    protected $fillable = [
        'user_id',
        'date_time'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
