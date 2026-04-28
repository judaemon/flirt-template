<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        $admin = User::updateOrCreate(
            ['company_email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => 'Password123!',
                'status' => 'active',
            ]
        );
        $admin->assignRole('super-admin');

        // Standard user
        $user = User::updateOrCreate(
            ['company_email' => 'user@example.com'],
            [
                'first_name' => 'Regular',
                'last_name' => 'User',
                'password' => 'Password123!',
                'status' => 'active',
            ]
        );
        $user->assignRole('user');
    }
}
