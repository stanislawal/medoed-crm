<?php

namespace App\Models\Lid;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LidSpecialistStatus extends Model
{
    use HasFactory;

    public $table = 'lid_specialist_statuses';

    protected $fillable = [
        'name',
        'color'
    ];

    public $timestamps = true;

    public function lids()
    {
        return $this->hasMany(Lid::class, 'lid_specialist_status_id');
    }
}
