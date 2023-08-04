<?php

namespace App\Console\Commands;

use App\Constants\NotificationTypeConstants;
use App\Http\Controllers\NotificationController;
use App\Models\Notification;
use App\Models\Project\Project;
use Illuminate\Console\Command;

class CheckProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверка проектов, по которым пора отписать клиенту';

    private NotificationController $notificationController;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(NotificationController $notificationController)
    {
        parent::__construct();
        $this->notificationController = $notificationController;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        $this->projectWeek();
//        $this->projectMonth();
        $this->payment();
    }

    /**
     * Создатьу уведомление по проектам, в которых дата последнего прописывания неделя
     *
     * @return void
     */
    private function projectWeek()
    {
        $date = now()->subDays(7)->format('Y-m-d');
        $currentDate = now()->format('Y-m-d');

        // получить проекты, по которым сегодня уже созданоу ведомление
        $notInProjectId = Notification::on()->selectRaw('distinct(project_id)')
            ->where('type', NotificationTypeConstants::WRITE_TO_CLIENT_WEEK)
            ->whereRaw("DATE(date_time) = '{$currentDate}'")->get()->pluck('project_id');

        $projectWeek = Project::on()->select(['id'])
            ->whereNotIn('status_id', [3, 5]) // кроме статусов "ушел", "стоп"
            ->whereNotNull('date_last_change')
            ->where('date_last_change', $date)
            ->whereNotIn('id', $notInProjectId)
            ->get()
            ->pluck('id');

        foreach ($projectWeek as $projectId) {
            $this->notificationController->createNotification(
                NotificationTypeConstants::WRITE_TO_CLIENT_WEEK,
                '',
                $projectId
            );
        }
    }

    /**
     * Создатьу уведомление по проектам, в которых дата последнего прописывания неделя
     *
     * @return void
     */
    private function projectMonth()
    {
        $date = now()->subDays(30)->format('Y-m-d');
        $currentDate = now()->format('Y-m-d');

        // получить проекты, по которым сегодня уже созданоу ведомление
        $notInProjectId = Notification::on()->selectRaw('distinct(project_id)')
            ->where('type', NotificationTypeConstants::WRITE_TO_CLIENT_MONTH)
            ->whereRaw("DATE(date_time) = '{$currentDate}'")->get()->pluck('project_id');

        $projectWeek = Project::on()->select(['id'])
            ->where('status_id', '3') // только статус "стоп"
            ->whereNotNull('date_last_change')
            ->where('date_last_change', $date)
            ->whereNotIn('id', $notInProjectId)
            ->get()
            ->pluck('id');

        foreach ($projectWeek as $projectId) {
            $this->notificationController->createNotification(
                NotificationTypeConstants::WRITE_TO_CLIENT_MONTH,
                '',
                $projectId
            );
        }
    }

    /**
     * уведомление об необходимости оплатить по проекту
     *
     * @return void
     */
    private function payment()
    {
        $projects = Project::on()->select(['id'])
            ->whereNotNull('date_notification')
            ->where('date_notification', now()->format('Y-m-d'))
            ->orWhere(function($where){
                $where->whereHas('notifiProject', function($where){
                    $where->whereIn('day', [
                        now()->format('j'),
                        now()->format('l')
                    ]);
                });
            })
            ->get()
            ->pluck('id');

        foreach ($projects as $project) {
            $this->notificationController->createNotification(
                NotificationTypeConstants::PROJECT_PAYMENT,
                '',
                $project
            );
        }
    }
}
