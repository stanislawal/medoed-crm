<?php

namespace Database\Seeders;

use App\Models\Lid\Audit;
use App\Models\Lid\CallUp;
use App\Models\Lid\LocationDialogue;
use App\Models\Lid\Resource;
use App\Models\Lid\Service;
use App\Models\Lid\SpecialistTask;
use http\Env\Response;
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
//        CallUp::on()->truncate();
//
//        $list = [
//            [
//                'name'  => 'Созвон',
//                'color' => '#d32525'
//            ],
//            [
//                'name'  => 'Прошел созвон',
//                'color' => '#0a9b2f'
//            ],
//            [
//                'name'  => 'Не прошел',
//                'color' => '#8f1ecb'
//            ]
//        ];
//
//        foreach ($list as $item) {
//            CallUp::on()->updateOrCreate([
//                'name' => $item['name']
//            ], $item);
//        }
//
//        Audit::on()->truncate();
//
//        $list = [
//            [
//                'name'  => 'Аудит назначен',
//                'color' => '#ffd825'
//            ],
//            [
//                'name'  => 'Аудит в работе',
//                'color' => '#6d9eeb'
//            ],
//            [
//                'name'  => 'Аудит проведен',
//                'color' => '#0a9b2f'
//            ],
//            [
//                'name'  => 'Аудит не сделан',
//                'color' => '#f37979'
//            ],
//            [
//                'name'  => 'Аудит в запасе',
//                'color' => '#b131f5'
//            ]
//        ];
//
//        foreach ($list as $item) {
//            Audit::on()->updateOrCreate([
//                'name' => $item['name']
//            ], $item);
//        }
//
//        SpecialistTask::on()->truncate();
//
//        $list = [
//            [
//                'name'  => 'Аудит',
//                'color' => ''
//            ],
//            [
//                'name'  => 'Ответ',
//                'color' => ''
//            ],
//            [
//                'name'  => 'Созвон',
//                'color' => ''
//            ]
//        ];
//
//        foreach ($list as $item) {
//            SpecialistTask::on()->updateOrCreate([
//                'name' => $item['name']
//            ], $item);
//        }

        Resource::on()->truncate();

        $list = [
            ['name' => 'Фл', 'color' => ''],
            ['name' => 'Телеграм', 'color' => ''],
            ['name' => 'Юду', 'color' => ''],
            ['name' => 'Профи', 'color' => ''],
            ['name' => 'Фриланс', 'color' => ''],
            ['name' => 'Кворк', 'color' => ''],
            ['name' => 'ЯУ', 'color' => ''],
            ['name' => 'Реклама кворк', 'color' => ''],
            ['name' => 'Реклама яу', 'color' => ''],
            ['name' => 'Таргет', 'color' => ''],
            ['name' => 'Сам', 'color' => ''],
            ['name' => 'Сарафан', 'color' => ''],
            ['name' => 'заявка ВК', 'color' => ''],
            ['name' => 'Сайт', 'color' => ''],
            ['name' => 'Юла', 'color' => ''],
            ['name' => 'Авито', 'color' => ''],
            ['name' => 'Хедхантер', 'color' => ''],
            ['name' => 'Реклама Авито', 'color' => ''],
            ['name' => 'Реклама фриланс ру', 'color' => ''],
            ['name' => 'реклама фл', 'color' => ''],
            ['name' => 'старый зак', 'color' => ''],
            ['name' => 'тенчат', 'color' => '']
        ];

        foreach ($list as $item) {
            Resource::on()->updateOrCreate([
                'name' => $item['name']
            ], $item);
        }


        Service::on()->truncate();

        $list = [
            ['name' => 'SEO'],
            ['name' => 'Контент-маркетинг'],
            ['name' => 'Контент'],
            ['name' => 'Дзен'],
            ['name' => 'блоги'],
            ['name' => 'SMM']
        ];

        foreach ($list as $item) {
            Service::on()->updateOrCreate([
                'name' => $item['name']
            ], $item);
        }


        LocationDialogue::on()->truncate();

        $list = [
            ['name' => 'тг2'],
            ['name' => 'тг4'],
            ['name' => 'тг кирилл'],
            ['name' => 'тг даша'],
            ['name' => 'тг1'],
            ['name' => 'вотсап к'],
            ['name' => 'вотсап д'],
            ['name' => 'вотсап а'],
            ['name' => 'вк д'],
            ['name' => 'вк а']
        ];

        foreach ($list as $item) {
            LocationDialogue::on()->updateOrCreate([
                'name' => $item['name']
            ], $item);
        }
    }
}
