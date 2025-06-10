<?php

namespace Database\Seeders;

use App\Models\Service\ServiceType;
use Illuminate\Database\Seeder;

class SpecialistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'КМ'],
            ['name' => 'СММ'],
            ['name' => 'СЕО'],
            ['name' => 'Дизайн']
        ];

        foreach ($data as $item) {
            ServiceType::on()->updateOrCreate(['name' => $item['name']], $item);
        }
    }
}
