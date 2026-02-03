#!/usr/bin/env php
<?php

/*
 * SCRIPT UNTUK SEED FACILITIES KE DATABASE PRODUCTION
 * 
 * Cara pakai:
 * 1. Upload file ini ke root folder project di Anymhost
 * 2. Jalankan via SSH: php seed_facilities.php
 *    ATAU via browser: https://arkanta.my.id/seed_facilities.php
 */

define('LARAVEL_START', microtime(true));

// Register The Auto Loader
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel and handle the command...
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$status = $kernel->call('db:seed', [
    '--class' => 'Database\\Seeders\\FacilitySeeder',
    '--force' => true, // Force untuk production
]);

echo "\n";
echo "=====================================\n";
echo "  FACILITY SEEDER EXECUTION RESULT  \n";
echo "=====================================\n";
echo "Status Code: " . $status . "\n";
echo ($status === 0 ? "✅ SUCCESS!" : "❌ FAILED!") . "\n";
echo "=====================================\n";
echo "\n";
echo "Silakan cek kembali halaman create room.\n";
echo "Facilities seharusnya sudah muncul!\n";
echo "\n";

$kernel->terminate(
    Illuminate\Http\Request::capture(),
    new Illuminate\Http\Response()
);

exit($status);
