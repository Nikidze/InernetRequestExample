<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'slug' => 'director',
                'name' => 'Директор',
                'permissions' => [
                    'platform.index' => true,
                    'platform.tariffs' => true,
                    'platform.locations' => true,
                    'platform.orders' => true,
                    'platform.users' => true,
                    'platform.roles' => true,
                ],
            ],
            [
                'slug' => 'operator',
                'name' => 'Оператор',
                'permissions' => [
                    'platform.index' => true,
                    'platform.orders' => true,
                    'platform.orders.view_all' => true,
                    'platform.orders.create' => true,
                    'platform.orders.edit' => true,
                    'platform.orders.update_status' => true,
                ],
            ],
            [
                'slug' => 'brigade',
                'name' => 'Бригада',
                'permissions' => [
                    'platform.index' => true,
                    'platform.orders' => true,
                    'platform.orders.view_assigned' => true,
                    'platform.orders.complete' => true,
                ],
            ],
        ];

        foreach ($roles as $role) {
            \Orchid\Platform\Models\Role::create($role);
        }
    }
}
