<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                "id" => 1,
                "name" => 'Администратор'
            ],
            [
                "id" => 2,
                "name" => 'Менеджер'
            ],
            [
                "id" => 3,
                "name" => 'Автор'
            ],
            [
                "id" => 4,
                "name" => 'Реклама'
            ]
        ];

        foreach($roles as $item){
            Role::on()->updateOrCreate(
                [
                    'id' => $item['id']
                ],
                $item
            );

        }
    }
}
