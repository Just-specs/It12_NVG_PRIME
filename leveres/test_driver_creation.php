<?php
// Test script to check driver creation on Railway
// Run with: railway run php test_driver_creation.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Railway Driver Creation Test ===\n\n";

// Test 1: Check database connection
echo "1. Testing database connection...\n";
try {
    DB::connection()->getPdo();
    echo "   ✓ Database connected successfully\n\n";
} catch (Exception $e) {
    echo "   ✗ Database connection failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 2: Check if drivers table exists
echo "2. Checking if drivers table exists...\n";
try {
    $count = DB::table('drivers')->count();
    echo "   ✓ Drivers table exists with $count records\n\n";
} catch (Exception $e) {
    echo "   ✗ Drivers table error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 3: Check table structure
echo "3. Checking drivers table structure...\n";
try {
    $columns = DB::select("SHOW COLUMNS FROM drivers");
    foreach ($columns as $column) {
        echo "   - $column->Field ($column->Type)\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n\n";
}

// Test 4: Try to create a test driver
echo "4. Attempting to create a test driver...\n";
try {
    $testDriver = \App\Models\Driver::create([
        'name' => 'Test Driver ' . time(),
        'mobile' => '09' . rand(100000000, 999999999),
        'license_number' => 'TEST' . rand(1000, 9999),
        'status' => 'available'
    ]);
    echo "   ✓ Driver created successfully!\n";
    echo "   ID: $testDriver->id\n";
    echo "   Name: $testDriver->name\n";
    echo "   License: $testDriver->license_number\n\n";
    
    // Clean up - delete the test driver
    $testDriver->delete();
    echo "   ✓ Test driver cleaned up\n\n";
} catch (Exception $e) {
    echo "   ✗ Failed to create driver: " . $e->getMessage() . "\n";
    echo "   Stack trace: " . $e->getTraceAsString() . "\n\n";
}

// Test 5: Check session configuration
echo "5. Checking session configuration...\n";
echo "   SESSION_DRIVER: " . config('session.driver') . "\n";
echo "   SESSION_LIFETIME: " . config('session.lifetime') . "\n";
echo "   SESSION_SECURE_COOKIE: " . (config('session.secure') ? 'true' : 'false') . "\n";
echo "   SESSION_SAME_SITE: " . config('session.same_site') . "\n";
echo "   SESSION_DOMAIN: " . config('session.domain') . "\n\n";

// Test 6: Check if sessions table exists
echo "6. Checking sessions table...\n";
try {
    $sessionCount = DB::table('sessions')->count();
    echo "   ✓ Sessions table exists with $sessionCount records\n\n";
} catch (Exception $e) {
    echo "   ✗ Sessions table error: " . $e->getMessage() . "\n\n";
}

echo "=== Test Complete ===\n";
