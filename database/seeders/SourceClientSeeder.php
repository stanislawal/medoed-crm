<?php

namespace Database\Seeders;

use App\Models\Client\SourceClient;
use Illuminate\Database\Seeder;

class SourceClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = [
            [
                'id' => 1,
                'name' => 'ФЛ'],
            [
                'id' => 2,
                'name' => 'Фрил'],
            [
                'id' => 3,
                'name' => 'Профи'],
            [
                'id' => 4,
                'name' => 'ЯУ'],
            [
                'id' => 5,
                'name' => 'ТГ'],
            [
                'id' => 6,
                'name' => 'Кворк'],
            [
                'id' => 7,
                'name' => 'Юду'],
            [
                'id' => 8,
                'name' => 'Авито'],
            [
                'id' => 9,
                'name' => 'ВК'],
        ];

        foreach ($date as $item) {
            SourceClient::on()->updateOrCreate([
                'id' => $item['id'],
            ], $item);
        }
    }
}
