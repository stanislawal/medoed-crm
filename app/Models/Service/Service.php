<?php

namespace App\Models\Service;

use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services_project';

    protected $fillable = [
        'project_id',
        'service_type_id',
        'project_theme',
        'reporting_data',
        'terms_payment',
        'region',
        'all_price',
        'accrual_this_month',
        'task',
        'link_to_work_plan'
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
}
