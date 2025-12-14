<?php
/**
 * TEMPORARY ADMIN CREATOR
 * Visit: https://your-app.up.railway.app/create-admin.php?secret=admin2025
 * DELETE IMMEDIATELY AFTER USE!
 */

$secret = $_GET['secret'] ?? '';
if ($secret !== 'admin2025') {
    die('Access denied');
}

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "<!DOCTYPE html><html><head><title>Create Admin</title></head><body>";
echo "<h2>?? Creating Admin User...</h2>";
echo "<pre style='background:#f5f5f5;padding:20px;'>";

try {
    // Check if admin already exists
    $existing = App\Models\User::where('email', 'admin@it12.com')->first();
    if ($existing) {
        echo "??  Admin user already exists!\n";
        echo "Email: admin@it12.com\n";
        echo "Reset password if needed.\n";
    } else {
        // Create admin user
        $user = new App\Models\User();
        $user->name = 'Admin User';
        $user->email = 'admin@it12.com';
        $user->password = Hash::make('Admin@123456');
        $user->role = 'admin';
        $user->email_verified_at = now();
        $user->save();

        echo "? Admin user created successfully!\n\n";
        echo "==========================================\n";
        echo "Login Credentials:\n";
        echo "==========================================\n";
        echo "Email: admin@it12.com\n";
        echo "Password: Admin@123456\n";
        echo "Role: admin\n";
        echo "==========================================\n\n";
        echo "??  CHANGE PASSWORD AFTER FIRST LOGIN!\n";
    }
    
} catch (Exception $e) {
    echo "? ERROR: " . $e->getMessage();
}

echo "</pre>";
echo "<div style='background:#ffebee;padding:20px;margin-top:20px;border-left:4px solid red;'>";
echo "<h3 style='color:red;'>?? DELETE THIS FILE NOW!</h3>";
echo "<p>Run: <code>git rm public/create-admin.php && git commit -m 'Remove admin creator' && git push</code></p>";
echo "</div></body></html>";
?>
