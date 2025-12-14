<?php
/**
 * DATABASE CONNECTION TEST
 * Visit: https://your-app.up.railway.app/check-database.php?secret=check2025
 */

$secret = $_GET['secret'] ?? '';
if ($secret !== 'check2025') {
    die('Access denied. Add ?secret=check2025');
}

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "<!DOCTYPE html><html><head><title>Database Check</title></head><body>";
echo "<h2>?? Database Connection Test</h2>";
echo "<pre style='background:#f5f5f5;padding:20px;border-radius:5px;'>";

try {
    echo "Step 1: Testing database connection...\n";
    echo "==========================================\n";
    
    $pdo = DB::connection()->getPdo();
    echo "? Database connected!\n\n";
    
    echo "Database Info:\n";
    echo "Host: " . config('database.connections.mysql.host') . "\n";
    echo "Port: " . config('database.connections.mysql.port') . "\n";
    echo "Database: " . config('database.connections.mysql.database') . "\n";
    echo "Username: " . config('database.connections.mysql.username') . "\n\n";
    
    echo "Step 2: Checking existing tables...\n";
    echo "==========================================\n";
    
    $tables = DB::select('SHOW TABLES');
    if (empty($tables)) {
        echo "? No tables found! Database is empty.\n\n";
    } else {
        echo "? Found " . count($tables) . " tables:\n";
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            echo "  - $tableName\n";
        }
        echo "\n";
    }
    
    echo "Step 3: Running migrations...\n";
    echo "==========================================\n";
    
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $status = $kernel->call('migrate', [
        '--force' => true,
        '--verbose' => true
    ]);
    
    echo $kernel->output();
    echo "\n";
    echo "Status: " . ($status === 0 ? "? SUCCESS" : "? FAILED (code: $status)") . "\n";
    
    echo "\nStep 4: Verifying tables after migration...\n";
    echo "==========================================\n";
    
    $tablesAfter = DB::select('SHOW TABLES');
    echo "Total tables: " . count($tablesAfter) . "\n";
    foreach ($tablesAfter as $table) {
        $tableName = array_values((array)$table)[0];
        $count = DB::table($tableName)->count();
        echo "  - $tableName ($count rows)\n";
    }
    
} catch (Exception $e) {
    echo "\n? ERROR:\n";
    echo $e->getMessage() . "\n\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString();
}

echo "</pre>";
echo "<div style='background:#fff3cd;padding:20px;margin-top:20px;border-left:4px solid #ffc107;'>";
echo "<h3>?? Instructions:</h3>";
echo "<p>If migrations succeeded, delete this file:</p>";
echo "<code>git rm public/check-database.php && git commit -m 'Remove check script' && git push</code>";
echo "</div>";
echo "</body></html>";
?>
