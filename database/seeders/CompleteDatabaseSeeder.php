<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BoardingHouse;
use App\Models\Room;
use App\Models\Facility;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CompleteDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Complete Database Seeding...');

        // 1. Create Users
        $this->command->info('👥 Creating users...');
        
        $admin = User::updateOrCreate(
            ['email' => 'admin@livora.com'],
            [
                'name' => 'Admin LIVORA',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Jakarta Pusat, DKI Jakarta',
                'email_verified_at' => now(),
            ]
        );

        $mitra1 = User::updateOrCreate(
            ['email' => 'owner@livora.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'phone' => '081234567891',
                'address' => 'Jakarta Selatan, DKI Jakarta',
                'email_verified_at' => now(),
            ]
        );

        $mitra2 = User::updateOrCreate(
            ['email' => 'sarah@livora.com'],
            [
                'name' => 'Sarah Wilson',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'phone' => '081234567892',
                'address' => 'Bandung, Jawa Barat',
                'email_verified_at' => now(),
            ]
        );

        $tenant1 = User::updateOrCreate(
            ['email' => 'tenant@livora.com'],
            [
                'name' => 'Jane Smith',
                'password' => Hash::make('password'),
                'role' => 'tenant',
                'phone' => '081234567893',
                'address' => 'Jakarta Timur, DKI Jakarta',
                'email_verified_at' => now(),
            ]
        );

        $tenant2 = User::updateOrCreate(
            ['email' => 'radit@gmail.com'],
            [
                'name' => 'Raditia Dika',
                'password' => Hash::make('password'),
                'role' => 'tenant',
                'phone' => '08122038199',
                'address' => 'Cijerah, Bandung',
                'email_verified_at' => now(),
            ]
        );

        $tenant3 = User::updateOrCreate(
            ['email' => 'budi@gmail.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'tenant',
                'phone' => '081234567894',
                'address' => 'Jakarta Barat, DKI Jakarta',
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Boarding Houses
        $this->command->info('🏠 Creating boarding houses...');
        
        $kost1 = BoardingHouse::updateOrCreate(
            ['slug' => 'kost-h-yayat'],
            [
                'user_id' => $mitra1->id,
                'name' => 'Kost H.Yayat',
                'address' => 'Jl.Inhoftank rt 07, Ciamis, Jawa Barat',
                'city' => 'Ciamis',
                'description' => 'Kost nyaman dan strategis di pusat kota Ciamis. Dekat dengan kampus, mall, dan transportasi umum. Fasilitas lengkap dengan WiFi, AC, dan kamar mandi dalam.',
                'latitude' => -7.3256,
                'longitude' => 108.3425,
                'is_active' => true,
            ]
        );

        $kost2 = BoardingHouse::updateOrCreate(
            ['slug' => 'kost-melati-residence'],
            [
                'user_id' => $mitra1->id,
                'name' => 'Kost Melati Residence',
                'address' => 'Jl. Melati No. 45, Jakarta Selatan',
                'city' => 'Jakarta',
                'description' => 'Kost eksklusif dengan fasilitas premium. Area aman dan nyaman dengan akses 24 jam. Cocok untuk mahasiswa dan pekerja profesional.',
                'latitude' => -6.2615,
                'longitude' => 106.8106,
                'is_active' => true,
            ]
        );

        $kost3 = BoardingHouse::updateOrCreate(
            ['slug' => 'kost-mawar-bandung'],
            [
                'user_id' => $mitra2->id,
                'name' => 'Kost Mawar Bandung',
                'address' => 'Jl. Dago No. 123, Bandung',
                'city' => 'Bandung',
                'description' => 'Kost minimalis modern di kawasan Dago yang sejuk. Dekat dengan ITB dan tempat wisata. Lingkungan tenang dan aman.',
                'latitude' => -6.8701,
                'longitude' => 107.6195,
                'is_active' => true,
            ]
        );

        // 3. Create Facilities (global, will be attached to rooms later)
        $this->command->info('✨ Creating facilities...');
        
        $wifi = Facility::updateOrCreate(
            ['name' => 'WiFi'],
            ['icon' => 'wifi', 'description' => 'Internet WiFi gratis']
        );
        
        $ac = Facility::updateOrCreate(
            ['name' => 'AC'],
            ['icon' => 'wind', 'description' => 'Air Conditioner']
        );
        
        $bathroom = Facility::updateOrCreate(
            ['name' => 'Kamar Mandi Dalam'],
            ['icon' => 'shower', 'description' => 'Kamar mandi di dalam kamar']
        );
        
        $parking = Facility::updateOrCreate(
            ['name' => 'Parkir Motor'],
            ['icon' => 'car', 'description' => 'Tempat parkir motor']
        );
        
        $laundry = Facility::updateOrCreate(
            ['name' => 'Laundry'],
            ['icon' => 'washing-machine', 'description' => 'Layanan cuci pakaian']
        );
        
        $kitchen = Facility::updateOrCreate(
            ['name' => 'Dapur Bersama'],
            ['icon' => 'utensils', 'description' => 'Dapur yang bisa digunakan bersama']
        );

        // 4. Create Rooms
        $this->command->info('🚪 Creating rooms...');
        
        $room1 = Room::create([
            'boarding_house_id' => $kost1->id,
            'name' => 'Kamar Premium A1',
            'price' => 15000000,
            'capacity' => 5,
            'size' => 20,
            'description' => 'Kamar luas dengan jendela besar, pemandangan bagus, AC, dan kamar mandi dalam.',
            'is_available' => false,
        ]);
        $room1->facilities()->attach([$wifi->id, $ac->id, $bathroom->id]);

        $room2 = Room::create([
            'boarding_house_id' => $kost2->id,
            'name' => 'Kamar Deluxe B1',
            'price' => 2500000,
            'capacity' => 2,
            'size' => 15,
            'description' => 'Kamar nyaman dengan AC, WiFi unlimited, meja kerja, dan lemari besar.',
            'is_available' => true,
        ]);
        $room2->facilities()->attach([$wifi->id, $ac->id, $bathroom->id, $parking->id, $laundry->id]);

        $room3 = Room::create([
            'boarding_house_id' => $kost2->id,
            'name' => 'Kamar Standard B2',
            'price' => 2000000,
            'capacity' => 1,
            'size' => 12,
            'description' => 'Kamar sederhana namun nyaman, dilengkapi dengan kipas angin dan kamar mandi luar.',
            'is_available' => true,
        ]);
        $room3->facilities()->attach([$wifi->id]);

        $room4 = Room::create([
            'boarding_house_id' => $kost3->id,
            'name' => 'Kamar Superior C1',
            'price' => 2200000,
            'capacity' => 2,
            'size' => 18,
            'description' => 'Kamar modern minimalis dengan balkon kecil, AC, dan view pegunungan.',
            'is_available' => false,
        ]);
        $room4->facilities()->attach([$wifi->id, $ac->id, $bathroom->id, $kitchen->id]);

        $room5 = Room::create([
            'boarding_house_id' => $kost3->id,
            'name' => 'Kamar Standard C2',
            'price' => 1800000,
            'capacity' => 1,
            'size' => 14,
            'description' => 'Kamar simple dan bersih dengan fasilitas dasar lengkap.',
            'is_available' => true,
        ]);
        $room5->facilities()->attach([$wifi->id, $bathroom->id]);

        // 5. Create Bookings
        $this->command->info('📅 Creating bookings...');
        
        $booking1 = Booking::create([
            'user_id' => $tenant2->id,
            'room_id' => $room1->id,
            'boarding_house_id' => $room1->boarding_house_id,
            'check_in_date' => Carbon::now()->subDays(10),
            'check_out_date' => Carbon::now()->addMonths(6),
            'duration_months' => 6,
            'duration_days' => 0,
            'monthly_price' => 15000000,
            'final_amount' => 90000000,
            'status' => 'active',
            'booking_type' => 'monthly',
            'tenant_identity_number' => '3201234567890123',
            'notes' => 'No. Hubungan: Orang Tua Catatan: biasa spesial',
        ]);

        $booking2 = Booking::create([
            'user_id' => $tenant1->id,
            'room_id' => $room4->id,
            'boarding_house_id' => $room4->boarding_house_id,
            'check_in_date' => Carbon::now()->subDays(5),
            'check_out_date' => Carbon::now()->addMonths(3),
            'duration_months' => 3,
            'duration_days' => 0,
            'monthly_price' => 2200000,
            'final_amount' => 6600000,
            'status' => 'confirmed',
            'booking_type' => 'monthly',
            'tenant_identity_number' => '3173012345678901',
            'notes' => 'Mohon kamar dibersihkan sebelum check-in',
        ]);

        $booking3 = Booking::create([
            'user_id' => $tenant3->id,
            'room_id' => $room2->id,
            'boarding_house_id' => $room2->boarding_house_id,
            'check_in_date' => Carbon::now()->addDays(7),
            'check_out_date' => Carbon::now()->addMonths(12)->addDays(7),
            'duration_months' => 12,
            'duration_days' => 0,
            'monthly_price' => 2500000,
            'final_amount' => 30000000,
            'status' => 'pending',
            'booking_type' => 'monthly',
            'tenant_identity_number' => '3171234567890123',
            'notes' => 'Butuh tempat parkir motor',
        ]);

        // 6. Create Payments
        $this->command->info('💰 Creating payments...');
        
        $payment1 = Payment::create([
            'booking_id' => $booking1->id,
            'amount' => 15000000,
            'payment_method' => 'transfer',
            'status' => 'verified',
            'payment_date' => Carbon::now()->subDays(9),
            'verified_at' => Carbon::now()->subDays(9),
            'verified_by' => $mitra1->id,
            'notes' => 'Pembayaran bulan pertama',
        ]);

        $payment2 = Payment::create([
            'booking_id' => $booking2->id,
            'amount' => 2200000,
            'payment_method' => 'transfer',
            'status' => 'verified',
            'payment_date' => Carbon::now()->subDays(4),
            'verified_at' => Carbon::now()->subDays(4),
            'verified_by' => $mitra2->id,
            'notes' => 'DP Booking',
        ]);

        $payment3 = Payment::create([
            'booking_id' => $booking3->id,
            'amount' => 2500000,
            'payment_method' => 'cash',
            'status' => 'pending',
            'payment_date' => Carbon::now()->subDays(1),
            'notes' => 'Menunggu verifikasi',
        ]);

        // 7. Create Tickets
        $this->command->info('🎫 Creating tickets...');
        
        Ticket::create([
            'user_id' => $tenant1->id,
            'booking_id' => $booking2->id,
            'subject' => 'AC Tidak Dingin',
            'description' => 'AC di kamar saya tidak dingin, sudah saya coba bersihkan filter tapi tetap tidak dingin. Mohon diperbaiki.',
            'priority' => 'high',
            'status' => 'in_progress',
            'created_at' => Carbon::now()->subDays(2),
        ]);

        Ticket::create([
            'user_id' => $tenant2->id,
            'booking_id' => $booking1->id,
            'subject' => 'Lampu Kamar Mandi Mati',
            'description' => 'Lampu di kamar mandi tidak menyala, sepertinya perlu diganti.',
            'priority' => 'medium',
            'status' => 'resolved',
            'resolved_at' => Carbon::now()->subDays(1),
            'created_at' => Carbon::now()->subDays(3),
        ]);

        Ticket::create([
            'user_id' => $tenant3->id,
            'booking_id' => $booking3->id,
            'subject' => 'Pertanyaan Fasilitas Laundry',
            'description' => 'Apakah ada fasilitas laundry? Jika ada, berapa biayanya?',
            'priority' => 'low',
            'status' => 'open',
            'created_at' => Carbon::now()->subHours(5),
        ]);

        // 8. Create Notifications
        $this->command->info('🔔 Creating notifications...');
        
        Notification::create([
            'user_id' => $mitra1->id,
            'type' => 'booking',
            'title' => 'Booking Baru',
            'message' => 'Anda memiliki booking baru dari Jane Smith untuk Kamar Superior C1',
            'read_at' => null,
        ]);

        Notification::create([
            'user_id' => $mitra1->id,
            'type' => 'payment',
            'title' => 'Pembayaran Diterima',
            'message' => 'Pembayaran sebesar Rp 15.000.000 telah diterima dari Raditia Dika',
            'read_at' => Carbon::now()->subDays(1),
        ]);

        Notification::create([
            'user_id' => $tenant1->id,
            'type' => 'ticket',
            'title' => 'Ticket Diproses',
            'message' => 'Ticket "AC Tidak Dingin" sedang dalam proses penanganan',
            'read_at' => null,
        ]);

        $this->command->info('');
        $this->command->info('✅ Complete Database Seeding Finished!');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info('   - Users: ' . User::count());
        $this->command->info('   - Boarding Houses: ' . BoardingHouse::count());
        $this->command->info('   - Rooms: ' . Room::count());
        $this->command->info('   - Facilities: ' . Facility::count());
        $this->command->info('   - Bookings: ' . Booking::count());
        $this->command->info('   - Payments: ' . Payment::count());
        $this->command->info('   - Tickets: ' . Ticket::count());
        $this->command->info('   - Notifications: ' . Notification::count());
        $this->command->info('');
        $this->command->info('🔑 Login Credentials:');
        $this->command->info('   Admin  : admin@livora.com | password');
        $this->command->info('   Mitra 1: owner@livora.com | password');
        $this->command->info('   Mitra 2: sarah@livora.com | password');
        $this->command->info('   Tenant 1: tenant@livora.com | password');
        $this->command->info('   Tenant 2: radit@gmail.com | password');
        $this->command->info('   Tenant 3: budi@gmail.com | password');
    }
}
