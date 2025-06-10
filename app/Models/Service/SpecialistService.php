<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialistService extends Model
{
    use HasFactory;

    protected $table = 'specialist_services';

    protected $fillable = [
        'name'
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, CrossServiceSpecialist::class, 'specialist_service_id', 'service_id');
    }
}
