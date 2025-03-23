<?php

namespace App\Models\Lid;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationDialogue extends Model
{
    use HasFactory;

    public $table = 'location_dialogues';

    protected $fillable = [
        'name'
    ];

    public $timestamps = true;

    public function lids()
    {
        return $this->hasMany(Lid::class, 'location_dialogue_id');
    }
}
