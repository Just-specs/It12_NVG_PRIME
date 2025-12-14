<?php
/**
 * TEMPORARY MIGRATION SCRIPT
 * DELETE THIS FILE AFTER RUNNING!
 * 
 * Visit: https://your-app.up.railway.app/run-migrations.php
 */

// Security: Only allow access with secret key
$secret = $_GET['secret'] ?? '';
if ($secret !== 'migrate_it12_2025') {
    die('Access denied');
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<h2>Running Migrations...</h2>";
echo "<pre>";

// Run migrations
$status = $kernel->call('migrate', [
    '--force' => true,
    '--verbose' => true
]);

echo "\n\nMigration Status: " . ($status === 0 ? 'SUCCESS ?' : 'FAILED ?');
echo "\n\nOutput:\n";
echo $kernel->output();

echo "</pre>";
echo "<h3 style='color: red;'>?? DELETE THIS FILE IMMEDIATELY!</h3>";
echo "<p>Run: git rm public/run-migrations.php && git commit -m 'Remove migration script' && git push</p>";
?>
