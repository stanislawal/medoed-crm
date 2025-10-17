<?php

namespace App\Console\Commands;

use App\Models\Service\Service;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer-service:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Перенос услуг на новый месяц';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // диапазон дат прошлого месяца
        $betweenOldMonth = [
            now()->subMonth()->startOfMonth()->toDateTimeString(),
            now()->subMonth()->endOfMonth()->toDateTimeString()
        ];

        // диапазон дат нового месяца
        $betweenNewMonth = [
            now()->startOfMonth()->toDateTimeString(),
            now()->endOfMonth()->toDateTimeString()
        ];

        $services = Service::on()
            ->selectRaw("
                id,
                name,
                project_id,
                service_type_id,
                all_price,
                accrual_this_month,
                task
            ")
            ->with(['specialists:id'])
            ->whereBetween('services_project.created_at', $betweenOldMonth)
            ->where('services_project.task', 'Сопровождение')
            ->whereNotExists(function ($query) use ($betweenNewMonth) {
                $query->select(DB::raw(1))
                    ->from('services_project as new_services')
                    ->whereColumn('new_services.project_id', 'services_project.project_id')
                    ->whereColumn('new_services.service_type_id', 'services_project.service_type_id')
                    ->whereRaw('LOWER(REPLACE(new_services.name, \' \', \'\')) = LOWER(REPLACE(services_project.name, \' \', \'\'))')
                    ->whereBetween('new_services.created_at', $betweenNewMonth);
            })
            ->get();

        DB::beginTransaction();
        try {
            foreach ($services as $item) {
                $service = Service::on()->create([
                    'name'               => $item->name,
                    'project_id'         => $item->project_id,
                    'service_type_id'    => $item->service_type_id,
                    'all_price'          => $item->all_price,
                    'accrual_this_month' => $item->accrual_this_month,
                    'task'               => $item->task,
                    'user_id'            => 54,
                ]);

                $service->specialists()->sync($item->specialists->pluck('id')->toArray());

                DB::commit();
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }
    }
}
