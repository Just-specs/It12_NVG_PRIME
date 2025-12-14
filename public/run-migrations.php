<?php
/**
 * TEMPORARY MIGRATION SCRIPT
 * Visit: https://your-app.up.railway.app/run-migrations.php?secret=migrate2025
 * DELETE IMMEDIATELY AFTER USE!
 */

$secret = $_GET['secret'] ?? '';
if ($secret !== 'migrate2025') {
    die('Access denied. Add ?secret=migrate2025 to URL');
}

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<!DOCTYPE html><html><head><title>Railway Migration</title></head><body>";
echo "<h2>??? Running Migrations on Railway...</h2>";
echo "<pre style='background:#f5f5f5;padding:20px;'>";

try {
    // Test database connection first
    echo "Testing database connection...\n";
    $pdo = DB::connection()->getPdo();
    echo "? Database connected successfully!\n\n";
    
    echo "Running migrations...\n";
    echo "==========================================\n";
    
    $status = $kernel->call('migrate', ['--force' => true, '--verbose' => true]);
    
    echo "\n==========================================\n";
    echo $status === 0 ? "? SUCCESS!" : "? FAILED";
    echo "\n==========================================\n\n";
    echo $kernel->output();
    
} catch (Exception $e) {
    echo "\n? ERROR: " . $e->getMessage();
}

echo "</pre>";
echo "<div style='background:#ffebee;padding:20px;margin-top:20px;border-left:4px solid red;'>";
echo "<h3 style='color:red;'>?? DELETE THIS FILE NOW!</h3>";
echo "<p>Run: <code>git rm public/run-migrations.php && git commit -m 'Remove migration script' && git push</code></p>";
echo "</div></body></html>";
?>
