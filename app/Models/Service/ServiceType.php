<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    protected $table = 'service_types';

    protected $fillable = [
        'name',
        'color'
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'service_type_id');
    }
}
