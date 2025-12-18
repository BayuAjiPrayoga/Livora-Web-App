<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            [
                'name' => 'WiFi',
                'icon' => '📶',
                'description' => 'Koneksi internet WiFi gratis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AC',
                'icon' => '❄️',
                'description' => 'Air Conditioner / Pendingin ruangan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kasur',
                'icon' => '🛏️',
                'description' => 'Tempat tidur dengan kasur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lemari',
                'icon' => '🚪',
                'description' => 'Lemari pakaian',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Meja Belajar',
                'icon' => '📚',
                'description' => 'Meja dan kursi untuk belajar/bekerja',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kamar Mandi Dalam',
                'icon' => '🚿',
                'description' => 'Kamar mandi pribadi di dalam kamar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jendela',
                'icon' => '🪟',
                'description' => 'Jendela untuk ventilasi dan cahaya alami',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'TV',
                'icon' => '📺',
                'description' => 'Televisi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kulkas',
                'icon' => '🧊',
                'description' => 'Lemari es / kulkas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kipas Angin',
                'icon' => '💨',
                'description' => 'Kipas angin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Water Heater',
                'icon' => '♨️',
                'description' => 'Pemanas air untuk mandi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Balkon',
                'icon' => '🏞️',
                'description' => 'Balkon atau teras pribadi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laundry',
                'icon' => '👕',
                'description' => 'Fasilitas laundry',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Parkir Motor',
                'icon' => '🏍️',
                'description' => 'Area parkir sepeda motor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Parkir Mobil',
                'icon' => '🚗',
                'description' => 'Area parkir mobil',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dapur Bersama',
                'icon' => '🍳',
                'description' => 'Dapur yang dapat digunakan bersama',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Listrik Termasuk',
                'icon' => '⚡',
                'description' => 'Biaya listrik sudah termasuk dalam harga sewa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CCTV',
                'icon' => '📹',
                'description' => 'Sistem keamanan CCTV',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('facilities')->insert($facilities);
    }
}
