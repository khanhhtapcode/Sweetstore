<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@sweetdelights.com'],
            [
                'name' => 'Admin Sweet Delights',
                'email' => 'admin@sweetdelights.com',
                'password' => Hash::make('admin123456'),
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created:');
        $this->command->info('Email: admin@sweetdelights.com');
        $this->command->info('Password: admin123456');

        // Create some sample users
        $users = [
            [
                'name' => 'Nguyễn Văn An',
                'email' => 'user1@example.com',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_USER,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Trần Thị Bình',
                'email' => 'user2@example.com',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_USER,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Lê Hoàng Cường',
                'email' => 'user3@example.com',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_USER,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('Sample users created with password: password123');
    }
}
