<?php

namespace Database\Seeders;

use App\Models\Requisite;
use Illuminate\Database\Seeder;

class RequisiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = [
            ['name' => 'ИП Алла'],
            ['name' => 'ИП даша'],
            ['name' => 'Тинькофф К.К'],
            ['name' => 'Сбре К.Г'],
            ['name' => 'Сбер К.К'],
            ['name' => 'Биржа'],
        ];

        foreach ($date as $item) {
            Requisite::on()->create($item);
        }
    }
}
