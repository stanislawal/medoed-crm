<?php

namespace App\Models\Lid;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialistTask extends Model
{
    use HasFactory;

    public $table = 'specialist_tasks';

    protected $fillable = [
        'name',
        'color'
    ];

    public $timestamps = true;

    public function lids()
    {
        return $this->hasMany(Lid::class, 'specialist_task_id');
    }
}
