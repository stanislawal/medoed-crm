<?php

namespace App\Http\Controllers\Report;

use App\Models\Project\Project;

class ReportService
{
    public function index()
    {
        $reports = Project::on()->selectRaw("
            projects.id,
            projects.manager_id,
            projects.project_name,
            projects.project_theme_service,
            projects.reporting_data,
            projects.terms_payment,
            projects.region
        ")
            ->with([
                'projectClients',
                'projectUser'
            ])
            ->from('projects')
            ->whereHas('services')
            ->paginate(20);

        $project = Project::on()->select(['id', 'project_name'])->get();

        return view('report.service.service_list', [
            'reports'  => $reports,
            'projects' => $project
        ]);
    }

    public function show($id)
    {
        return view('report.service.service_item');
    }
}
