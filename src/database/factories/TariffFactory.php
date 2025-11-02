<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tariff>
 */
class TariffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Базовый',
                'Скорый',
                'Мгновенный',
                'Экспресс',
                'Премиум',
            ]),
            'speed' => fake()->randomElement([10, 25, 50, 100, 150, 300]),
            'price' => fake()->numberBetween(500, 3000),
            'is_active' => fake()->boolean(85),
        ];
    }

    /**
     * Indicate that the tariff is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the tariff is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the tariff has high speed.
     */
    public function highSpeed(): static
    {
        return $this->state(fn (array $attributes) => [
            'speed' => fake()->randomElement([100, 200, 500, 1000]),
        ]);
    }
}
