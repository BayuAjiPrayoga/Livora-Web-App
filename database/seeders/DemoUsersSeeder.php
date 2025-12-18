<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@livora.com'],
            [
                'name' => 'Admin LIVORA',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Jakarta',
            ]
        );

        // Create Mitra (Owner)
        User::updateOrCreate(
            ['email' => 'owner@livora.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'phone' => '081234567891',
                'address' => 'Jakarta Selatan',
            ]
        );

        // Create Tenant
        User::updateOrCreate(
            ['email' => 'tenant@livora.com'],
            [
                'name' => 'Jane Smith',
                'password' => Hash::make('password'),
                'role' => 'tenant',
                'phone' => '081234567892',
                'address' => 'Jakarta Pusat',
            ]
        );

        $this->command->info('Demo users created successfully!');
        $this->command->info('Admin: admin@livora.com | password');
        $this->command->info('Mitra: owner@livora.com | password');
        $this->command->info('Tenant: tenant@livora.com | password');
    }
}
