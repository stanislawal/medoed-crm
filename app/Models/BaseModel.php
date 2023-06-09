<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d.m.Y H:i');
    }
}
