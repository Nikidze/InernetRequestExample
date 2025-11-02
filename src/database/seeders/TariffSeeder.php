<?php

namespace Database\Seeders;

use App\Models\Tariff;
use Illuminate\Database\Seeder;

class TariffSeeder extends Seeder
{
    public function run(): void
    {
        $tariffs = [
            ['name' => 'Базовый', 'speed' => 10, 'price' => 500, 'is_active' => true],
            ['name' => 'Скорый', 'speed' => 50, 'price' => 1000, 'is_active' => true],
            ['name' => 'Мгновенный', 'speed' => 150, 'price' => 2000, 'is_active' => true],
            ['name' => 'Экспресс', 'speed' => 25, 'price' => 750, 'is_active' => true],
            ['name' => 'Премиум', 'speed' => 300, 'price' => 3000, 'is_active' => false],
        ];

        foreach ($tariffs as $tariff) {
            Tariff::create($tariff);
        }
    }
}
