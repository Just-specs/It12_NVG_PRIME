<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Railway Migration</h1>";
echo "<pre>";

try {
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    
    echo "? Laravel loaded\n\n";
    
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    
    echo "Running: php artisan migrate --force\n";
    echo "==========================================\n";
    
    $exitCode = $kernel->call('migrate', ['--force' => true]);
    
    echo $kernel->output();
    echo "\n==========================================\n";
    echo "Exit code: $exitCode\n";
    echo ($exitCode === 0 ? "? SUCCESS!" : "? FAILED!");
    
} catch (Throwable $e) {
    echo "? ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}

echo "</pre>";
echo "<p style='color:red;'><strong>DELETE THIS FILE: public/migrate-simple.php</strong></p>";
?>
