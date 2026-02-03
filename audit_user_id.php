<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== AUDIT USER ID TYPE ===\n\n";

// Check property ID 2
$property = \App\Models\BoardingHouse::find(2);
if ($property) {
    echo "Property ID 2 found:\n";
    echo "- user_id value: " . $property->user_id . "\n";
    echo "- user_id type: " . gettype($property->user_id) . "\n";
    echo "- owner name: " . ($property->owner ? $property->owner->name : 'N/A') . "\n";
    echo "- owner email: " . ($property->owner ? $property->owner->email : 'N/A') . "\n\n";
} else {
    echo "Property ID 2 NOT FOUND\n\n";
}

// Check all properties
echo "All Properties:\n";
$allProperties = \App\Models\BoardingHouse::with('owner')->get();
foreach ($allProperties as $prop) {
    echo "- ID: {$prop->id}, user_id: {$prop->user_id} (type: " . gettype($prop->user_id) . "), Owner: " . ($prop->owner ? $prop->owner->name : 'N/A') . "\n";
}
echo "\n";

// Check all users
echo "All Users:\n";
$allUsers = \App\Models\User::all();
foreach ($allUsers as $user) {
    echo "- ID: {$user->id} (type: " . gettype($user->id) . "), Name: {$user->name}, Role: {$user->role}\n";
}
echo "\n";

// Test comparison
echo "=== COMPARISON TESTS ===\n";
if ($property) {
    $userId = $property->user_id;
    $testId = 1;
    
    echo "Testing: property->user_id ({$userId}, " . gettype($userId) . ") vs testId ({$testId}, " . gettype($testId) . ")\n";
    echo "- Strict (===): " . ($userId === $testId ? 'TRUE' : 'FALSE') . "\n";
    echo "- Non-Strict (==): " . ($userId == $testId ? 'TRUE' : 'FALSE') . "\n";
    echo "- Strict NOT (!==): " . ($userId !== $testId ? 'TRUE' : 'FALSE') . "\n";
    echo "- Non-Strict NOT (!=): " . ($userId != $testId ? 'TRUE' : 'FALSE') . "\n";
}
