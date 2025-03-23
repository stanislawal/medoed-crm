<?php

namespace App\Models\Lid;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public $table = 'services';

    protected $fillable = [
        'name'
    ];

    public $timestamps = true;

    public function lids()
    {
        return $this->hasMany(Lid::class, 'service_id');
    }
}
