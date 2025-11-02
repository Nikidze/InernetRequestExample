<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['name' => 'Мирный', 'is_active' => true],
            ['name' => 'Трудовой', 'is_active' => true],
            ['name' => 'Майский', 'is_active' => true],
            ['name' => 'Пролетарский', 'is_active' => false],
            ['name' => 'Комсомольский', 'is_active' => false],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
