<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\BoardingHouse;
use App\Models\Room;
use App\Models\Facility;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LightweightDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder will create:
     * - 1 Admin
     * - 5 Owners (Bayu, Damar, Fauzi, Sofia, Nazwa)
     * - 20 Tenants
     * - 10 Boarding Houses (2 per owner)
     * - 30 Rooms (3 per boarding house)
     * - 15 Facilities
     * - 25 Bookings
     * - 30 Payments
     * - 15 Tickets
     * - 20 Notifications
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting lightweight data seeding...');

        // 1. Create Admin
        $this->command->info('Creating admin user...');
        $admin = User::create([
            'name' => 'Admin Livora',
            'email' => 'admin@livora.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin Raya No. 1, Jakarta Pusat',
            'email_verified_at' => now(),
        ]);
        $this->command->info('✓ Admin created');

        // 2. Create 5 Owners
        $this->command->info('Creating 5 owners...');
        $owners = [];
        $cities = ['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Semarang'];
        
        $ownerData = [
            ['name' => 'Bayu Aji Prayoga', 'email' => 'bayu@livora.com', 'city' => 'Jakarta'],
            ['name' => 'Damar Wicaksono', 'email' => 'damar@livora.com', 'city' => 'Bandung'],
            ['name' => 'Fauzi Rahman', 'email' => 'fauzi@livora.com', 'city' => 'Surabaya'],
            ['name' => 'Sofia Maharani', 'email' => 'sofia@livora.com', 'city' => 'Yogyakarta'],
            ['name' => 'Nazwa Putri', 'email' => 'nazwa@livora.com', 'city' => 'Semarang'],
        ];
        
        foreach ($ownerData as $data) {
            $owners[] = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'owner',
                'phone' => '08' . rand(1000000000, 9999999999),
                'address' => "Jl. " . explode(' ', $data['name'])[0] . " Raya No. " . rand(1, 50) . ", {$data['city']}",
                'email_verified_at' => now(),
            ]);
        }
        $this->command->info('✓ 5 Owners created');

        // 3. Create 20 Tenants
        $this->command->info('Creating 20 tenants...');
        $tenants = [];
        
        $tenantNames = [
            'Andi Pratama', 'Siti Nurhaliza', 'Budi Santoso', 'Dewi Lestari', 'Rizki Ramadhan',
            'Fitri Handayani', 'Agus Setiawan', 'Linda Wulandari', 'Hendra Kusuma', 'Mega Anggraini',
            'Rudi Hermawan', 'Indah Permata', 'Dimas Prasetyo', 'Kartika Sari', 'Fajar Hidayat',
            'Ayu Lestari', 'Tono Sumarno', 'Nina Rahayu', 'Eko Budiman', 'Putri Azzahra'
        ];
        
        for ($i = 0; $i < 20; $i++) {
            $name = $tenantNames[$i];
            $emailName = strtolower(str_replace(' ', '.', $name));
            $city = $cities[array_rand($cities)];
            
            $tenants[] = User::create([
                'name' => $name,
                'email' => $emailName . '@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'tenant',
                'phone' => '08' . rand(1000000000, 9999999999),
                'address' => "Jl. " . explode(' ', $name)[1] . " No. " . rand(1, 100) . ", {$city}",
                'email_verified_at' => now(),
            ]);
        }
        $this->command->info('✓ 20 Tenants created');

        // 4. Create Facilities
        $this->command->info('Creating facilities...');
        $facilityNames = [
            'WiFi Gratis', 'AC', 'Kamar Mandi Dalam', 'Lemari Pakaian', 'Meja Belajar',
            'Kasur', 'Bantal & Guling', 'Parkir Motor', 'Parkir Mobil', 'Dapur Bersama',
            'Mesin Cuci', 'Jemuran', 'CCTV', 'Security 24 Jam', 'Listrik Termasuk'
        ];
        $facilities = [];
        foreach ($facilityNames as $index => $name) {
            $facilities[] = Facility::create([
                'name' => $name,
                'icon' => 'facility-icon-' . ($index + 1) . '.svg',
            ]);
        }
        $this->command->info('✓ 15 Facilities created');

        // 5. Create 10 Boarding Houses (2 per owner)
        $this->command->info('Creating 10 boarding houses...');
        $boardingHouses = [];
        $houseTypes = ['Kost Putri', 'Kost Putra', 'Kost Campur', 'Kost Premium'];
        
        foreach ($owners as $index => $owner) {
            $city = $ownerData[$index]['city'];
            
            // Create 2 boarding houses per owner
            for ($h = 1; $h <= 2; $h++) {
                $type = $houseTypes[array_rand($houseTypes)];
                $name = "{$type} {$city} " . chr(64 + $h);
                
                $boardingHouses[] = BoardingHouse::create([
                    'user_id' => $owner->id,
                    'name' => $name,
                    'slug' => Str::slug($name) . '-' . Str::random(4),
                    'address' => "Jl. {$city} Raya No. " . rand(1, 100) . ", {$city}",
                    'city' => $city,
                    'description' => "Kost nyaman dan strategis di {$city}. Dekat dengan kampus, mall, dan transportasi umum. Fasilitas lengkap dan harga terjangkau.",
                    'latitude' => -6.2 + (rand(-50, 50) / 100),
                    'longitude' => 106.8 + (rand(-50, 50) / 100),
                    'images' => [
                        'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=800',
                        'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800',
                        'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800',
                    ],
                    'is_active' => true,
                ]);
            }
        }
        $this->command->info('✓ 10 Boarding Houses created');

        // 6. Create 30 Rooms (3 per boarding house)
        $this->command->info('Creating 30 rooms...');
        $rooms = [];
        $roomTypes = ['A', 'B', 'C', 'VIP', 'Standard'];
        
        foreach ($boardingHouses as $house) {
            // Create 3 rooms per boarding house
            for ($r = 1; $r <= 3; $r++) {
                $type = $roomTypes[array_rand($roomTypes)];
                $basePrice = rand(800, 2500) * 1000; // 800k - 2.5jt
                
                $room = Room::create([
                    'boarding_house_id' => $house->id,
                    'name' => "Kamar {$type} {$r}",
                    'description' => "Kamar type {$type} dengan fasilitas lengkap. Nyaman untuk tinggal jangka panjang.",
                    'price' => $basePrice,
                    'capacity' => rand(1, 2),
                    'size' => rand(12, 20),
                    'images' => [
                        'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800',
                        'https://images.unsplash.com/photo-1540518614846-7eded433c457?w=800',
                    ],
                    'is_available' => rand(0, 10) > 2, // 80% available
                ]);
                
                // Attach 6-10 random facilities to each room
                $randomFacilities = collect($facilities)->random(rand(6, 10))->pluck('id');
                $room->facilities()->attach($randomFacilities);
                
                $rooms[] = $room;
            }
        }
        $this->command->info('✓ 30 Rooms created');

        // 7. Create 25 Bookings
        $this->command->info('Creating 25 bookings...');
        $bookingStatuses = [
            'pending' => 5,
            'confirmed' => 8,
            'active' => 7,
            'completed' => 4,
            'cancelled' => 1,
        ];
        
        $bookings = [];
        
        foreach ($bookingStatuses as $status => $count) {
            for ($i = 0; $i < $count; $i++) {
                $tenant = $tenants[array_rand($tenants)];
                $room = $rooms[array_rand($rooms)];
                $duration = rand(1, 6); // 1-6 months
                
                $startDate = Carbon::now()->subMonths(3)->addDays(rand(0, 90));
                $endDate = $startDate->copy()->addMonths($duration);
                
                $totalPrice = $room->price * $duration;
                
                $booking = Booking::create([
                    'user_id' => $tenant->id,
                    'room_id' => $room->id,
                    'start_date' => $startDate->format('Y-m-d'),
                    'duration' => $duration,
                    'end_date' => $endDate->format('Y-m-d'),
                    'total_price' => $totalPrice,
                    'final_amount' => $totalPrice,
                    'status' => $status,
                    'notes' => $status === 'cancelled' ? 'Dibatalkan karena perubahan rencana' : null,
                ]);
                
                $bookings[] = $booking;
            }
        }
        $this->command->info('✓ 25 Bookings created');

        // 8. Create Payments
        $this->command->info('Creating payments...');
        $paymentCount = 0;
        
        foreach ($bookings as $booking) {
            if ($booking->status === 'pending') {
                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->total_price,
                    'status' => 'pending',
                    'proof_image' => null,
                    'notes' => null,
                    'verified_at' => null,
                ]);
                $paymentCount++;
            }
            elseif (in_array($booking->status, ['confirmed', 'active', 'completed'])) {
                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->total_price,
                    'status' => 'verified',
                    'proof_image' => 'proof-images/payment-' . rand(1, 50) . '.jpg',
                    'notes' => 'Pembayaran telah diverifikasi',
                    'verified_at' => $booking->created_at->addHours(rand(1, 24)),
                ]);
                $paymentCount++;
            }
            elseif ($booking->status === 'cancelled') {
                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->total_price,
                    'status' => 'rejected',
                    'proof_image' => 'proof-images/payment-' . rand(1, 50) . '.jpg',
                    'notes' => 'Pembayaran dibatalkan',
                    'verified_at' => $booking->created_at->addHours(rand(1, 12)),
                ]);
                $paymentCount++;
            }
        }
        $this->command->info("✓ {$paymentCount} Payments created");

        // 9. Create 15 Tickets
        $this->command->info('Creating 15 support tickets...');
        $ticketStatuses = ['open', 'in_progress', 'resolved', 'closed'];
        $subjects = [
            'AC Tidak Dingin', 'Keran Air Rusak', 'WiFi Lemot', 'Perpanjangan Sewa',
            'Kamar Kotor', 'Parkir Penuh', 'Listrik Mati', 'Request Furniture',
            'Komplain Tetangga', 'Info Fasilitas', 'Konfirmasi Pembayaran', 'Lampu Rusak',
            'Perbaikan Pintu', 'Request AC', 'Tanya Aturan Kost'
        ];
        
        for ($i = 0; $i < 15; $i++) {
            $tenant = $tenants[array_rand($tenants)];
            $room = $rooms[array_rand($rooms)];
            $status = $ticketStatuses[array_rand($ticketStatuses)];
            $subject = $subjects[array_rand($subjects)];
            
            Ticket::create([
                'user_id' => $tenant->id,
                'room_id' => $room->id,
                'subject' => $subject,
                'message' => "Mohon bantuan untuk {$subject}. Terima kasih.",
                'status' => $status,
                'priority' => collect(['low', 'medium', 'high'])->random(),
                'response' => in_array($status, ['resolved', 'closed']) ? 'Terima kasih, masalah telah ditangani.' : null,
                'resolved_at' => in_array($status, ['resolved', 'closed']) ? now()->subDays(rand(1, 15)) : null,
            ]);
        }
        $this->command->info('✓ 15 Tickets created');

        // 10. Create 20 Notifications
        $this->command->info('Creating 20 notifications...');
        $allUsers = User::all();
        $notificationTypes = [
            'booking_created' => 'Booking baru telah dibuat',
            'booking_confirmed' => 'Booking Anda telah dikonfirmasi',
            'payment_received' => 'Pembayaran diterima',
            'ticket_created' => 'Tiket support baru',
            'ticket_resolved' => 'Tiket telah diselesaikan',
            'reminder' => 'Pengingat pembayaran',
        ];
        
        for ($i = 0; $i < 20; $i++) {
            $user = $allUsers->random();
            $type = array_rand($notificationTypes);
            $isRead = rand(0, 10) > 4;
            
            Notification::create([
                'user_id' => $user->id,
                'title' => $notificationTypes[$type],
                'message' => "Detail notifikasi untuk {$notificationTypes[$type]}.",
                'type' => $type,
                'read_at' => $isRead ? now()->subDays(rand(1, 15)) : null,
                'priority' => collect(['low', 'medium', 'high'])->random(),
            ]);
        }
        $this->command->info('✓ 20 Notifications created');

        // Summary
        $this->command->info('');
        $this->command->info('🎉 Lightweight seeding completed successfully!');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info("   - Users: " . User::count() . " (1 Admin, 5 Owners, 20 Tenants)");
        $this->command->info("   - Boarding Houses: " . BoardingHouse::count());
        $this->command->info("   - Rooms: " . Room::count());
        $this->command->info("   - Facilities: " . Facility::count());
        $this->command->info("   - Bookings: " . Booking::count());
        $this->command->info("   - Payments: " . Payment::count());
        $this->command->info("   - Tickets: " . Ticket::count());
        $this->command->info("   - Notifications: " . Notification::count());
        $this->command->info('');
        $this->command->info('✨ Owner accounts:');
        $this->command->info('   - bayu@livora.com (Bayu Aji Prayoga - Jakarta)');
        $this->command->info('   - damar@livora.com (Damar Wicaksono - Bandung)');
        $this->command->info('   - fauzi@livora.com (Fauzi Rahman - Surabaya)');
        $this->command->info('   - sofia@livora.com (Sofia Maharani - Yogyakarta)');
        $this->command->info('   - nazwa@livora.com (Nazwa Putri - Semarang)');
        $this->command->info('');
    }
}
