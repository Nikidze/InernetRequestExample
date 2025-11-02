<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Location;
use App\Models\Order;
use App\Models\Tariff;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $locations = Location::all();
        $tariffs = Tariff::all();
        $operators = User::whereHas('roles', function ($query) {
            $query->where('slug', 'operator');
        })->get();

        $brigades = User::whereHas('roles', function ($query) {
            $query->where('slug', 'brigade');
        })->get();

        $orders = [
            [
                'client_name' => 'Петров Иван Иванович',
                'client_phone' => '+79001234567',
                'address_full' => 'ул. Ленина, д. 15, кв. 10',
                'status' => OrderStatus::NEW->value,
                'connection_time' => now()->addDays(2),
            ],
            [
                'client_name' => 'Сидорова Елена Викторовна',
                'client_phone' => '+79007654321',
                'address_full' => 'ул. Пушкина, д. 25, кв. 5',
                'status' => OrderStatus::IN_PROGRESS->value,
                'connection_time' => now()->addDays(1),
            ],
            [
                'client_name' => 'Иванов Сергей Петрович',
                'client_phone' => '+79005551234',
                'address_full' => 'ул. Гагарина, д. 8, кв. 20',
                'status' => OrderStatus::COMPLETED->value,
                'connection_time' => now()->subDays(1),
            ],
        ];

        foreach ($orders as $orderData) {
            $order = new Order($orderData);
            $order->location_id = $locations->random()->id;
            $order->tariff_id = $tariffs->random()->id;
            $order->operator_id = $operators->random()->id;

            if ($order->status !== OrderStatus::NEW->value) {
                $order->brigade_id = $brigades->random()->id;
            }

            $order->save();
        }
    }
}
