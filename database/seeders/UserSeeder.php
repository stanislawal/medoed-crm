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
                'full_name' => 'Петров Кирилл Юрьевич',
                'login' => 'admin',
                'password' => Hash::make('12345678'),
                'is_work' => 'true',
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
