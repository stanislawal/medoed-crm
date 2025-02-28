<?php

namespace Database\Seeders;

use App\Models\Lid\Audit;
use App\Models\Lid\CallUp;
use App\Models\Lid\SpecialistTask;
use Illuminate\Database\Seeder;

class LidAllSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CallUp::on()->truncate();

        $list = [
            [
                'name' => 'Созвон',
                'color' => '#d32525'
            ],
            [
                'name' => 'Прошел созвон',
                'color' => '#0a9b2f'
            ],
            [
                'name' => 'Не прошел',
                'color' => '#8f1ecb'
            ]
        ];

        foreach ($list as $item) {
            CallUp::on()->updateOrCreate([
                'name' => $item['name']
            ], $item);
        }

        Audit::on()->truncate();

        $list = [
            [
                'name' => 'Аудит назначен',
                'color' => '#ffd825'
            ],
            [
                'name' => 'Аудит в работе',
                'color' => '#6d9eeb'
            ],
            [
                'name' => 'Аудит проведен',
                'color' => '#0a9b2f'
            ],
            [
                'name' => 'Аудит не сделан',
                'color' => '#f37979'
            ],
            [
                'name' => 'Аудит в запасе',
                'color' => '#b131f5'
            ]
        ];

        foreach ($list as $item) {
            Audit::on()->updateOrCreate([
                'name' => $item['name']
            ], $item);
        }

        SpecialistTask::on()->truncate();

        $list = [
            [
                'name' => 'Аудит',
                'color' => ''
            ],
            [
                'name' => 'Ответ',
                'color' => ''
            ],
            [
                'name' => 'Созвон',
                'color' => ''
            ]
        ];

        foreach ($list as $item) {
            SpecialistTask::on()->updateOrCreate([
                'name' => $item['name']
            ], $item);
        }
    }
}
