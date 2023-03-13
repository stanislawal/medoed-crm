<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = [
            [
                'full_name' => 'Гонтарь Кирилл Владимирович',
                'login' => 'admin',
                'password' => Hash::make('12345678'),
                'is_work' => 1,
            ]
        ];

        foreach ($admins as $item) {
            $user = User::updateOrCreate(
                ['login' => $item['login']],
                $item
            );
            $user->syncRoles('Администратор');
        }
    }
}
