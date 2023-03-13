<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                "id" => 1,
                "name" => 'На проверке',
                "color" => '#ece13985'
            ],
            [
                "id" => 2,
                "name" => 'Ожидание ТЗ',
                "color" => '#ffad46c7'
            ],
            [
                "id" => 3,
                "name" => 'Стоп',
                "color" => '#827cd0bf'
            ],
            [
                "id" => 4,
                "name" => 'Доработка',
                "color" => '#ff8e00d4'
            ],
            [
                "id" => 5,
                "name" => 'Ушел',
                "color" => '#f25959c7'
            ],
            [
                "id" => 6,
                "name" => 'Ждем оплату',
                "color" => '#59d0f28f'
            ],
            [
                "id" => 7,
                "name" => 'В работе',
                "color" => '#68dd75d1'
            ]

        ];

        foreach($statuses as $item){
            Status::on()->updateOrCreate(
                [
                    'id' => $item['id']
                ],
                $item
            );
        }
    }
}
