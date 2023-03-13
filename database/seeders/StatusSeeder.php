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
                "name" => 'Черновик',
            ],
            [
                "id" => 2,
                "name" => 'В работе',
            ],
            [
                "id" => 3,
                "name" => 'Завершен',
            ],
            [
                "id" => 4,
                "name" => 'Отказ',
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
