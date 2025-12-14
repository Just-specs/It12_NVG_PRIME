<?php
/**
 * TEMPORARY MIGRATION SCRIPT
 * DELETE THIS FILE AFTER RUNNING!
 * 
 * Visit: https://your-app.up.railway.app/run-migrations.php?secret=migrate_it12_2025
 */

// Security: Only allow access with secret key
$secret = $_GET['secret'] ?? '';
if ($secret !== 'migrate_it12_2025') {
    die('Access denied. Add ?secret=migrate_it12_2025 to URL');
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<!DOCTYPE html><html><head><title>Migration Script</title></head><body>";
echo "<h2>??? Running Database Migrations...</h2>";
echo "<pre style='background: #f5f5f5; padding: 20px; border-radius: 5px;'>";

try {
    // Run migrations
    $status = $kernel->call('migrate', [
        '--force' => true,
        '--verbose' => true
    ]);

    echo "\n\n";
    echo "==============================================\n";
    echo "Migration Status: " . ($status === 0 ? '? SUCCESS' : '? FAILED') . "\n";
    echo "==============================================\n\n";
    echo "Output:\n";
    echo $kernel->output();
    
} catch (Exception $e) {
    echo "\n\n? ERROR: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString();
}

echo "</pre>";
echo "<div style='background: #ffebee; padding: 20px; border-left: 4px solid #f44336; margin-top: 20px;'>";
echo "<h3 style='color: #c62828; margin-top: 0;'>?? SECURITY WARNING</h3>";
echo "<p><strong>DELETE THIS FILE IMMEDIATELY after migrations complete!</strong></p>";
echo "<p>Run these commands:</p>";
echo "<pre>git rm public/run-migrations.php\ngit commit -m 'Remove migration script'\ngit push origin main</pre>";
echo "</div>";
echo "</body></html>";
?>
