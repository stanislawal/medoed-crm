<?php

namespace Database\Seeders;

use App\Models\StatusPayment;
use App\Models\StatusPaymentProject;
use Illuminate\Database\Seeder;

class StatusPaymentSeeder extends Seeder
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
                'name' => 'Разобраться',
                'color' => '#2ebb2e'
            ],
            [
                'name' => 'Нет в разноске',
                'color' => '#2cc3c0'
            ],
            [
                'name' => 'Нет денег',
                'color' => '#bd1717'
            ],
            [
                'name' => 'Готово',
                'color' => '#d4e51f'
            ],
        ];

        foreach ($statuses as $status) {
            StatusPayment::on()->create($status);
        }

    }
}
