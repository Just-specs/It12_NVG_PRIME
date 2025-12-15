<?php
/**
 * Export Database Schema for Railway
 * Run: php export-schema.php
 */

require __DIR__.'/vendor/autoload.php';

$migrations = glob(__DIR__.'/database/migrations/*.php');

$sql = "-- IT12 Dispatch System Database Schema\n";
$sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
$sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

foreach ($migrations as $migration) {
    $sql .= "-- " . basename($migration) . "\n";
    $content = file_get_contents($migration);
    
    // Extract table creation from migration
    if (preg_match('/Schema::create\([\'"]([^\'"]+)[\'"]/', $content, $matches)) {
        $tableName = $matches[1];
        $sql .= "-- Creating table: $tableName\n";
    }
}

$sql .= "\nSET FOREIGN_KEY_CHECKS=1;\n";

echo "Migration files found: " . count($migrations) . "\n";
echo "SQL file would be complex to auto-generate.\n";
echo "Better approach: Use Railway's web script.\n";
?>
