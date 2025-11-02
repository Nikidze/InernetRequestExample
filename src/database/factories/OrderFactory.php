<?php

namespace DatabaseFactories;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_name' => fake()->name(),
            'client_phone' => fake()->phoneNumber(),
            'address_full' => fake()->streetAddress().', кв. '.fake()->buildingNumber(),
            'status' => fake()->randomElement(OrderStatus::cases())->value,
            'connection_time' => fake()->optional()->dateTimeBetween('now', '+1 month'),
            'settlement_id' => \App\Models\Location::factory(),
            'tariff_id' => \App\Models\Tariff::factory(),
            'operator_id' => \App\Models\User::factory(),
            'brigade_id' => fake()->boolean(70) ? \App\Models\User::factory() : null,
        ];
    }

    public function new(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::NEW->value,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::IN_PROGRESS->value,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::COMPLETED->value,
        ]);
    }

    public function canceled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::CANCELED->value,
        ]);
    }

    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'connection_time' => fake()->dateTimeBetween('now', '+1 month'),
        ]);
    }
}
