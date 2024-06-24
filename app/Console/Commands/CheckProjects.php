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
        $this->connectWithClient();
        $this->projectWeek();
        $this->projectMonth();
        $this->payment();
    }

    /**
     * Создание уведомления даты связи с клиентом
     *
     * @return void
     */
    private function connectWithClient()
    {
        $date = now()->format('Y-m-d');

        $projects = Project::on()->select(['id', 'manager_id'])
            ->whereNotNull('date_connect_with_client')
            ->where('date_connect_with_client', $date)
            ->whereNotIn('status_id', [2, 3, 5]) // кроме статусов "Ожидается ТЗ", "Стоп", "Ушел"
            ->get();

        foreach ($projects as $project) {
            $this->notificationController->createNotification(
                NotificationTypeConstants::DATE_CONTACT_WITH_CLIENT,
                $project->manager_id,
                $project->id
            );
        }
    }

    /**
     * Создать уведомление по проектам, в которых дата последнего прописывания неделя
     *
     * @return void
     */
    private function projectWeek()
    {
        $date = now()->subDays(7)->format('Y-m-d');
        $currentDate = now()->format('Y-m-d');

        // получить проекты, по которым сегодня уже создано уведомление
        $notInProjectId = Notification::on()->selectRaw('distinct(project_id)')
            ->where('type', NotificationTypeConstants::WRITE_TO_CLIENT_WEEK)
            ->whereRaw("DATE(date_time) = '{$currentDate}'")->get()->pluck('project_id');

        $projectWeek = Project::on()->select(['id', 'manager_id'])
            ->whereNotIn('status_id', [5]) // кроме статусов "Ушел"
            ->whereNotNull('date_last_change')
            ->where('date_last_change', $date)
            ->whereNotIn('id', $notInProjectId)
            ->get();

        foreach ($projectWeek as $project) {
            $this->notificationController->createNotification(
                NotificationTypeConstants::WRITE_TO_CLIENT_WEEK,
                $project->manager_id,
                $project->id
            );
        }
    }

    /**
     * Создать уведомление по проектам, в которых дата последнего прописывания месяц
     *
     * @return void
     */
    private function projectMonth()
    {
        $date = now()->subDays(30)->format('Y-m-d');
        $currentDate = now()->format('Y-m-d');

        // получить проекты, по которым сегодня уже создано уведомление
        $notInProjectId = Notification::on()->selectRaw('distinct(project_id)')
            ->where('type', NotificationTypeConstants::WRITE_TO_CLIENT_MONTH)
            ->whereRaw("DATE(date_time) = '{$currentDate}'")->get()->pluck('project_id');

        $projectWeek = Project::on()->select(['id', 'manager_id'])
            ->whereNotIn('status_id', [5]) // кроме статусов "Ушел"
            ->whereNotNull('date_last_change')
            ->where('date_last_change', $date)
            ->whereNotIn('id', $notInProjectId)
            ->get();

        foreach ($projectWeek as $project) {
            $this->notificationController->createNotification(
                NotificationTypeConstants::WRITE_TO_CLIENT_MONTH,
                $project->manager_id,
                $project->id
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
        $projects = Project::on()->select(['id', 'manager_id'])
            ->whereNotNull('date_notification')
            ->whereNotIn('status_id', [5]) //  кроме статусов "ушел"
            ->where('date_notification', now()->format('Y-m-d'))
            ->orWhere(function ($where) {
                $where->whereHas('notifiProject', function ($where) {
                    $where->whereIn('day', [
                        now()->format('j'),
                        now()->format('l')
                    ]);
                });
            })
            ->get();

        foreach ($projects as $project) {
            $this->notificationController->createNotification(
                NotificationTypeConstants::PROJECT_PAYMENT,
                $project->manager_id,
                $project->id
            );
        }
    }
}
