<?php
/**
 * SHOW RAILWAY DATABASE CONFIG
 * Visit: https://your-app.up.railway.app/show-config.php
 * Shows what database Railway is trying to use
 */

echo "<h1>Railway Database Configuration</h1>";
echo "<pre style='background:#f5f5f5;padding:20px;'>";

echo "Environment Variables:\n";
echo "==========================================\n";
echo "DB_CONNECTION: " . (getenv('DB_CONNECTION') ?: 'NOT SET') . "\n";
echo "DB_HOST: " . (getenv('DB_HOST') ?: 'NOT SET') . "\n";
echo "DB_PORT: " . (getenv('DB_PORT') ?: 'NOT SET') . "\n";
echo "DB_DATABASE: " . (getenv('DB_DATABASE') ?: 'NOT SET') . "\n";
echo "DB_USERNAME: " . (getenv('DB_USERNAME') ?: 'NOT SET') . "\n";
echo "DB_PASSWORD: " . (getenv('DB_PASSWORD') ? '***SET***' : 'NOT SET') . "\n\n";

echo "MySQL Service Variables:\n";
echo "==========================================\n";
echo "MYSQLHOST: " . (getenv('MYSQLHOST') ?: 'NOT SET') . "\n";
echo "MYSQLPORT: " . (getenv('MYSQLPORT') ?: 'NOT SET') . "\n";
echo "MYSQLDATABASE: " . (getenv('MYSQLDATABASE') ?: 'NOT SET') . "\n";
echo "MYSQLUSER: " . (getenv('MYSQLUSER') ?: 'NOT SET') . "\n";
echo "MYSQLPASSWORD: " . (getenv('MYSQLPASSWORD') ? '***SET***' : 'NOT SET') . "\n\n";

try {
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    
    echo "Laravel Config (what Laravel sees):\n";
    echo "==========================================\n";
    echo "DB_HOST: " . config('database.connections.mysql.host') . "\n";
    echo "DB_PORT: " . config('database.connections.mysql.port') . "\n";
    echo "DB_DATABASE: " . config('database.connections.mysql.database') . "\n";
    echo "DB_USERNAME: " . config('database.connections.mysql.username') . "\n";
    echo "DB_PASSWORD: " . (config('database.connections.mysql.password') ? '***SET***' : 'NOT SET') . "\n";
    
} catch (Exception $e) {
    echo "Error loading Laravel: " . $e->getMessage();
}

echo "</pre>";
echo "<p style='color:red;'><strong>DELETE THIS FILE after checking!</strong></p>";
?>
