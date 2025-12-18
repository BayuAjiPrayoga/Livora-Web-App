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

class MassiveDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder will create:
     * - 1 Admin
     * - 50 Owners (Mitra)
     * - 200 Tenants
     * - 150 Boarding Houses (3 per owner on average)
     * - 600 Rooms (4 per boarding house on average)
     * - 15 Facilities
     * - 800 Bookings (mix of all statuses)
     * - 800+ Payments
     * - 300 Tickets
     * - 500 Notifications
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting massive data seeding...');

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

        // 2. Create Owners (Mitra)
        $this->command->info('Creating 50 owners...');
        $owners = [];
        $cities = [
            'Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Semarang',
            'Malang', 'Solo', 'Denpasar', 'Medan', 'Makassar',
            'Palembang', 'Tangerang', 'Depok', 'Bekasi', 'Bogor'
        ];
        
        $ownerNames = [
            'Budi Santoso', 'Ahmad Wijaya', 'Siti Nurhaliza', 'Dewi Lestari', 'Eko Prasetyo',
            'Fitri Handayani', 'Gunawan Hartono', 'Hendra Kusuma', 'Indah Permata', 'Joko Widodo',
            'Kartika Sari', 'Linda Agustina', 'Muhammad Rizki', 'Nurul Hidayah', 'Oki Setiana',
            'Putri Maharani', 'Qori Sandioriva', 'Rudi Hermawan', 'Sari Dewi', 'Tono Sumarno',
            'Usman Abdullah', 'Vina Panduwinata', 'Wati Kurniawan', 'Xavier Nugraha', 'Yanti Kusuma',
            'Zainal Abidin', 'Ayu Ting Ting', 'Bambang Pamungkas', 'Citra Kirana', 'Dedi Mulyadi',
            'Evi Masamba', 'Fahmi Ardiansyah', 'Gina Rachman', 'Hadi Pranoto', 'Ika Natassa',
            'Johan Budi', 'Kiki Amalia', 'Luki Permana', 'Mira Lesmana', 'Nina Zatulini',
            'Oscar Lawalata', 'Prita Hapsari', 'Qory Putri', 'Rina Gunawan', 'Sinta Nuriyah',
            'Tika Dewi', 'Ucok Baba', 'Vero Wulandari', 'Widi Mulia', 'Yudi Latif'
        ];
        
        for ($i = 0; $i < 50; $i++) {
            $city = $cities[array_rand($cities)];
            $name = $ownerNames[$i];
            $email = strtolower(str_replace(' ', '.', $name)) . '@livora.com';
            
            $owners[] = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'owner',
                'phone' => '08' . rand(1000000000, 9999999999),
                'address' => "Jl. " . explode(' ', $name)[0] . " Raya No. " . rand(1, 99) . ", {$city}",
                'email_verified_at' => now(),
            ]);
        }
        $this->command->info('✓ 50 Owners created');

        // 3. Create Tenants
        $this->command->info('Creating 200 tenants...');
        $tenants = [];
        
        $firstNames = [
            'Agus', 'Andi', 'Bambang', 'Budi', 'Dedi', 'Eko', 'Fajar', 'Hadi', 'Joko', 'Rudi',
            'Aisyah', 'Dewi', 'Fitri', 'Hana', 'Indah', 'Kartika', 'Linda', 'Mega', 'Nina', 'Putri',
            'Reza', 'Rizki', 'Sandi', 'Tono', 'Udin', 'Wawan', 'Yanto', 'Zainal', 'Amir', 'Bayu',
            'Cindy', 'Dina', 'Ella', 'Fika', 'Gita', 'Hesti', 'Ika', 'Juita', 'Kiki', 'Lina',
            'Arif', 'Dimas', 'Gilang', 'Irfan', 'Krisna', 'Lukman', 'Nanda', 'Rafi', 'Yoga', 'Zaki'
        ];
        
        $lastNames = [
            'Pratama', 'Santoso', 'Wijaya', 'Kusuma', 'Saputra', 'Wibowo', 'Nugroho', 'Firmansyah',
            'Hidayat', 'Prasetyo', 'Setiawan', 'Permana', 'Ramadhan', 'Fadillah', 'Maulana', 'Hakim',
            'Kurniawan', 'Syahputra', 'Rahman', 'Ibrahim', 'Suryanto', 'Budiman', 'Hermawan', 'Gunawan',
            'Purnomo', 'Indrayana', 'Mahendra', 'Sanjaya', 'Irawan', 'Laksono', 'Wardana', 'Adiputra',
            'Sasmita', 'Perdana', 'Anggara', 'Maharani', 'Lestari', 'Handayani', 'Rahayu', 'Wulandari',
            'Safitri', 'Anggraini', 'Putri', 'Azzahra', 'Kamila', 'Oktaviani', 'Rahmawati', 'Damayanti',
            'Puspita', 'Suci', 'Utami', 'Ayu', 'Sari', 'Novita', 'Aditya', 'Putra', 'Surya', 'Cahya'
        ];
        
        for ($i = 0; $i < 200; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $name = $firstName . ' ' . $lastName;
            
            // Buat email yang unik dengan ID
            $emailBase = strtolower($firstName . '.' . $lastName);
            $email = $emailBase . '.' . ($i + 1) . '@gmail.com';
            
            $city = $cities[array_rand($cities)];
            
            $tenants[] = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'tenant',
                'phone' => '08' . rand(1000000000, 9999999999),
                'address' => "Jl. " . $lastName . " No. " . rand(1, 150) . ", {$city}",
                'email_verified_at' => now(),
            ]);
        }
        $this->command->info('✓ 200 Tenants created');

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

        // 5. Create Boarding Houses
        $this->command->info('Creating 150 boarding houses...');
        $boardingHouses = [];
        $houseTypes = [
            'Kost Putri', 'Kost Putra', 'Kost Campur', 'Kost Eksklusif',
            'Kost Budget', 'Kost Premium', 'Kost Mahasiswa'
        ];
        
        foreach ($owners as $index => $owner) {
            // Each owner has 2-4 boarding houses
            $houseCount = rand(2, 4);
            for ($h = 1; $h <= $houseCount && count($boardingHouses) < 150; $h++) {
                $city = $cities[array_rand($cities)];
                $type = $houseTypes[array_rand($houseTypes)];
                $name = "{$type} {$city} " . chr(64 + $h);
                
                $boardingHouses[] = BoardingHouse::create([
                    'user_id' => $owner->id,
                    'name' => $name,
                    'slug' => Str::slug($name) . '-' . Str::random(4),
                    'address' => "Jl. {$city} Raya No. " . rand(1, 200) . ", {$city}",
                    'city' => $city,
                    'description' => "Kost nyaman dan strategis di {$city}. Dekat dengan kampus, mall, dan transportasi umum. Fasilitas lengkap dan harga terjangkau.",
                    'latitude' => -6.2 + (rand(-100, 100) / 100),
                    'longitude' => 106.8 + (rand(-100, 100) / 100),
                    'images' => [
                        'properties/house-' . rand(1, 10) . '.jpg',
                        'properties/house-' . rand(1, 10) . '-2.jpg',
                        'properties/house-' . rand(1, 10) . '-3.jpg',
                    ],
                    'is_active' => rand(0, 10) > 1, // 90% active
                ]);
            }
        }
        $this->command->info('✓ ' . count($boardingHouses) . ' Boarding Houses created');

        // 6. Create Rooms
        $this->command->info('Creating 600 rooms...');
        $rooms = [];
        $roomTypes = ['A', 'B', 'C', 'D', 'VIP', 'Deluxe', 'Standard', 'Economy'];
        
        foreach ($boardingHouses as $house) {
            // Each boarding house has 3-6 rooms
            $roomCount = rand(3, 6);
            for ($r = 1; $r <= $roomCount && count($rooms) < 600; $r++) {
                $type = $roomTypes[array_rand($roomTypes)];
                $basePrice = rand(500, 3000) * 1000; // 500k - 3jt
                
                $room = Room::create([
                    'boarding_house_id' => $house->id,
                    'name' => "Kamar {$type} {$r}",
                    'description' => "Kamar type {$type} dengan fasilitas lengkap. Nyaman untuk tinggal jangka panjang.",
                    'price' => $basePrice,
                    'capacity' => rand(1, 2),
                    'size' => rand(9, 25),
                    'images' => [
                        'rooms/room-' . rand(1, 15) . '.jpg',
                        'rooms/room-' . rand(1, 15) . '-2.jpg',
                    ],
                    'is_available' => rand(0, 10) > 3, // 70% available
                ]);
                
                // Attach 5-12 random facilities to each room
                $randomFacilities = collect($facilities)->random(rand(5, 12))->pluck('id');
                $room->facilities()->attach($randomFacilities);
                
                $rooms[] = $room;
            }
        }
        $this->command->info('✓ ' . count($rooms) . ' Rooms created');

        // 7. Create Bookings
        $this->command->info('Creating 800 bookings...');
        $bookingStatuses = [
            'pending' => 150,      // 150 pending bookings
            'confirmed' => 250,    // 250 confirmed bookings
            'active' => 200,       // 200 active (checked in)
            'completed' => 150,    // 150 completed (checked out)
            'cancelled' => 50,     // 50 cancelled
        ];
        
        $bookings = [];
        $bookingIndex = 0;
        
        foreach ($bookingStatuses as $status => $count) {
            for ($i = 0; $i < $count; $i++) {
                $tenant = $tenants[array_rand($tenants)];
                $room = $rooms[array_rand($rooms)];
                $duration = rand(1, 12); // 1-12 months
                
                // Random start date between 6 months ago and 3 months future
                $startDate = Carbon::now()->subMonths(6)->addDays(rand(0, 270));
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
                $bookingIndex++;
            }
        }
        $this->command->info('✓ 800 Bookings created');

        // 8. Create Payments
        $this->command->info('Creating payments...');
        $paymentCount = 0;
        
        foreach ($bookings as $booking) {
            // Pending bookings: 1 pending payment
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
            
            // Confirmed, active, completed: Full payment
            elseif (in_array($booking->status, ['confirmed', 'active', 'completed'])) {
                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->total_price,
                    'status' => 'verified',
                    'proof_image' => rand(0, 10) > 2 ? 'proof-images/payment-' . rand(1, 100) . '.jpg' : null,
                    'notes' => 'Pembayaran telah diverifikasi',
                    'verified_at' => $booking->created_at->addHours(rand(1, 48)),
                ]);
                $paymentCount++;
                
                // 30% chance of having installment payments
                if (rand(0, 100) < 30 && $booking->duration > 3) {
                    $installments = rand(2, min(4, $booking->duration));
                    $installmentAmount = floor($booking->total_price / $installments);
                    
                    for ($p = 1; $p < $installments; $p++) {
                        Payment::create([
                            'booking_id' => $booking->id,
                            'amount' => $installmentAmount,
                            'status' => rand(0, 10) > 2 ? 'verified' : 'pending',
                            'proof_image' => 'proof-images/payment-installment-' . rand(1, 100) . '.jpg',
                            'notes' => "Cicilan ke-{$p}",
                            'verified_at' => $booking->created_at->addMonths($p)->addDays(rand(1, 5)),
                        ]);
                        $paymentCount++;
                    }
                }
            }
            
            // Cancelled: Might have payment or not
            elseif ($booking->status === 'cancelled') {
                if (rand(0, 10) > 5) {
                    Payment::create([
                        'booking_id' => $booking->id,
                        'amount' => $booking->total_price,
                        'status' => 'rejected',
                        'proof_image' => 'proof-images/payment-' . rand(1, 100) . '.jpg',
                        'notes' => 'Pembayaran dibatalkan',
                        'verified_at' => $booking->created_at->addHours(rand(1, 24)),
                    ]);
                    $paymentCount++;
                }
            }
        }
        $this->command->info("✓ {$paymentCount} Payments created");

        // 9. Create Tickets
        $this->command->info('Creating 300 support tickets...');
        $ticketStatuses = ['open', 'in_progress', 'resolved', 'closed'];
        
        for ($i = 0; $i < 300; $i++) {
            // 70% tickets from bookings, 30% general tickets
            if (rand(0, 10) > 3 && !empty($bookings)) {
                $booking = $bookings[array_rand($bookings)];
                $tenant = $booking->user;
                $room = $booking->room;
            } else {
                $tenant = $tenants[array_rand($tenants)];
                $room = $rooms[array_rand($rooms)];
            }
            
            $status = $ticketStatuses[array_rand($ticketStatuses)];
            
            $subjects = [
                'AC Tidak Dingin', 'Keran Air Rusak', 'Lampu Mati', 'Pintu Rusak',
                'Kamar Kotor', 'Tetangga Berisik', 'WiFi Lemot', 'Fasilitas Tidak Sesuai',
                'Perpanjangan Sewa', 'Info Fasilitas', 'Cara Pembayaran', 'Aturan Kost',
                'Request AC', 'Tambah Furniture', 'Perbaikan WiFi', 'Parkir Penuh',
                'Konfirmasi Transfer', 'Tanya Biaya', 'Request Invoice', 'Komplain Harga',
            ];
            
            $subject = $subjects[array_rand($subjects)];
            
            Ticket::create([
                'user_id' => $tenant->id,
                'room_id' => $room->id,
                'subject' => $subject,
                'message' => "Deskripsi lengkap mengenai {$subject}. Mohon segera ditindaklanjuti. Terima kasih.",
                'status' => $status,
                'priority' => collect(['low', 'medium', 'high'])->random(),
                'response' => in_array($status, ['resolved', 'closed']) ? 'Terima kasih atas laporannya. Masalah telah kami tangani.' : null,
                'resolved_at' => in_array($status, ['resolved', 'closed']) ? now()->subDays(rand(1, 30)) : null,
            ]);
        }
        $this->command->info('✓ 300 Tickets created');

        // 10. Create Notifications
        $this->command->info('Creating 500 notifications...');
        $allUsers = User::all();
        $notificationTypes = [
            'booking_created' => 'Booking baru telah dibuat',
            'booking_confirmed' => 'Booking Anda telah dikonfirmasi',
            'payment_received' => 'Pembayaran diterima',
            'ticket_created' => 'Tiket support baru',
            'ticket_resolved' => 'Tiket telah diselesaikan',
            'reminder' => 'Pengingat pembayaran',
        ];
        
        for ($i = 0; $i < 500; $i++) {
            $user = $allUsers->random();
            $type = array_rand($notificationTypes);
            $isRead = rand(0, 10) > 4; // 60% read
            
            Notification::create([
                'user_id' => $user->id,
                'title' => $notificationTypes[$type],
                'message' => "Detail notifikasi untuk {$notificationTypes[$type]}. Silakan cek dashboard Anda untuk informasi lebih lanjut.",
                'type' => $type,
                'read_at' => $isRead ? now()->subDays(rand(1, 30)) : null,
                'priority' => collect(['low', 'medium', 'high'])->random(),
            ]);
        }
        $this->command->info('✓ 500 Notifications created');

        // Summary
        $this->command->info('');
        $this->command->info('🎉 Seeding completed successfully!');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info("   - Users: " . User::count() . " (1 Admin, " . User::where('role', 'owner')->count() . " Owners, " . User::where('role', 'tenant')->count() . " Tenants)");
        $this->command->info("   - Boarding Houses: " . BoardingHouse::count());
        $this->command->info("   - Rooms: " . Room::count());
        $this->command->info("   - Facilities: " . Facility::count());
        $this->command->info("   - Bookings: " . Booking::count() . " (" . 
            Booking::where('status', 'pending')->count() . " pending, " .
            Booking::where('status', 'confirmed')->count() . " confirmed, " .
            Booking::where('status', 'active')->count() . " active, " .
            Booking::where('status', 'completed')->count() . " completed, " .
            Booking::where('status', 'cancelled')->count() . " cancelled)");
        $this->command->info("   - Payments: " . Payment::count());
        $this->command->info("   - Tickets: " . Ticket::count());
        $this->command->info("   - Notifications: " . Notification::count());
        $this->command->info('');
    }
}
