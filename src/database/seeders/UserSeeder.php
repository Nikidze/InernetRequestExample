<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Иван Директоров',
                'email' => 'director@example.com',
                'password' => 'password',
                'role' => 'director',
            ],
            [
                'name' => 'Мария Операторовна',
                'email' => 'operator@example.com',
                'password' => 'password',
                'role' => 'operator',
            ],
            [
                'name' => 'Алексей Бригадир',
                'email' => 'brigade@example.com',
                'password' => 'password',
                'role' => 'brigade',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                ]
            );

            $role = \Orchid\Platform\Models\Role::where('slug', $userData['role'])->first();
            if ($role) {
                $user->roles()->sync([$role->id]);

                // Добавляем базовые права пользователю
                $user->permissions = $role->permissions;
                $user->save();
            }
        }
    }
}
