<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Сбербанк'],
            ['name' => 'ЮMoney'],
            ['name' => 'Тинькофф'],
        ];

        foreach ($data as $bank) {
            Bank::on()->updateOrCreate($bank, $bank);
        }
    }
}
