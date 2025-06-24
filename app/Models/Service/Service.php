<?php

namespace App\Models\Service;

use App\Models\Project\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services_project';

    protected $fillable = [
        'project_id',
        'service_type_id',
        'all_price',
        'accrual_this_month',
        'task',
        'user_id',
        'name'
    ];


    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function serviceType(){
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    public function specialists()
    {
        return $this->belongsToMany(SpecialistService::class, CrossServiceSpecialist::class, 'service_id', 'specialist_service_id');
    }

    public function createdUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
