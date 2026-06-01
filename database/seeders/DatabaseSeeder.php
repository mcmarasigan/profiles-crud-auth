<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the database with an admin account and sample users.
     */
    public function run(): void
    {
        // Create the default admin account
        User::firstOrCreate(
            ['email' => 'admin@profilesauth.local'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('Admin@1234'),
                'role'     => 'admin',
                'bio'      => 'System administrator account.',
            ]
        );

        // Create sample regular users
        $users = [
            ['name' => 'Maria Santos',   'email' => 'maria@example.com',   'bio' => 'Web developer from Manila.'],
            ['name' => 'Juan dela Cruz', 'email' => 'juan@example.com',    'bio' => 'Full-stack developer.'],
            ['name' => 'Ana Reyes',      'email' => 'ana@example.com',     'bio' => 'UI/UX Designer.'],
            ['name' => 'Carlos Garcia',  'email' => 'carlos@example.com',  'bio' => 'Backend engineer.'],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => Hash::make('User@1234'),
                    'role'     => 'user',
                ])
            );
        }
    }
}
