<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LivoraSampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = \App\Models\User::create([
            'name' => 'Admin LIVORA',
            'email' => 'admin@livora.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '081234567888',
            'address' => 'Jakarta Pusat'
        ]);

        // Create Owner User
        $owner = \App\Models\User::create([
            'name' => 'John Doe',
            'email' => 'owner@livora.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'phone' => '081234567890',
            'address' => 'Jakarta Selatan'
        ]);

        // Create Tenant User
        $tenant = \App\Models\User::create([
            'name' => 'Jane Smith',
            'email' => 'tenant@livora.com',
            'password' => bcrypt('password'),
            'role' => 'tenant',
            'phone' => '081234567891',
            'address' => 'Jakarta Pusat'
        ]);

        // Create Boarding House
        $boardingHouse = \App\Models\BoardingHouse::create([
            'user_id' => $owner->id,
            'name' => 'Kost Elite LIVORA',
            'slug' => 'kost-elite-livora',
            'address' => 'Jl. Sudirman No. 123, Jakarta Selatan',
            'city' => 'Jakarta',
            'description' => 'Kost mewah dengan fasilitas lengkap di pusat kota Jakarta',
            'is_active' => true
        ]);

        // Create Rooms
        $room1 = \App\Models\Room::create([
            'boarding_house_id' => $boardingHouse->id,
            'name' => 'Kamar Deluxe A1',
            'description' => 'Kamar mewah dengan AC, WiFi, dan kamar mandi dalam',
            'price' => 3500000,
            'capacity' => 1,
            'size' => 25.5,
            'is_available' => false
        ]);

        $room2 = \App\Models\Room::create([
            'boarding_house_id' => $boardingHouse->id,
            'name' => 'Kamar Standard B2',
            'description' => 'Kamar nyaman dengan fasilitas standar',
            'price' => 2500000,
            'capacity' => 1,
            'size' => 20.0,
            'is_available' => true
        ]);

        // Create Bookings
        \App\Models\Booking::create([
            'user_id' => $tenant->id,
            'room_id' => $room1->id,
            'check_in_date' => now()->subDays(15),
            'check_out_date' => now()->addMonths(6)->subDays(15),
            'duration_months' => 6,
            'monthly_price' => 3500000,
            'total_amount' => 3500000 * 6,
            'final_amount' => 3500000 * 6,
            'status' => 'checked_in',
            'notes' => 'Booking untuk 6 bulan'
        ]);

        \App\Models\Booking::create([
            'user_id' => $tenant->id,
            'room_id' => $room2->id,
            'check_in_date' => now()->subDays(30),
            'check_out_date' => now()->addMonths(3)->subDays(30),
            'duration_months' => 3,
            'monthly_price' => 2500000,
            'total_amount' => 2500000 * 3,
            'final_amount' => 2500000 * 3,
            'status' => 'confirmed',
            'notes' => 'Booking jangka pendek'
        ]);

        // Create Tickets
        \App\Models\Ticket::create([
            'user_id' => $tenant->id,
            'room_id' => $room1->id,
            'subject' => 'AC Tidak Dingin',
            'message' => 'AC di kamar saya tidak dingin, mohon segera diperbaiki',
            'status' => 'open',
            'priority' => 'high'
        ]);
    }
}
