<?php

namespace App\Services;

use App\Models\Driver;
use Illuminate\Support\Facades\Log;

class DriverImportService
{
    private $errors = [];
    
    /**
     * List of drivers from Excel file (manually extracted)
     * Update this list if Excel file changes
     */
    private $excelDrivers = [
        'ALANG',
        'CANETE',
        'INTRUZO',
        'LAURENTE',
        'RIVERA',
        'SERENO',
        'TOCMO'
    ];
    
    /**
     * Import drivers that don't exist in database
     */
    public function importMissingDrivers(): array
    {
        $imported = 0;
        $skipped = 0;
        $importedDrivers = [];
        
        foreach ($this->excelDrivers as $name) {
            $result = $this->importDriver($name);
            
            if ($result['success']) {
                $imported++;
                $importedDrivers[] = $result['driver'];
            } else {
                $skipped++;
                $this->errors[] = $result['message'];
            }
        }
        
        return [
            'success' => true,
            'imported' => $imported,
            'skipped' => $skipped,
            'drivers' => $importedDrivers,
            'errors' => $this->errors
        ];
    }
    
    /**
     * Import a single driver
     */
    private function importDriver(string $name): array
    {
        $normalizedName = $this->normalizeName($name);
        
        // Check if driver already exists
        $existing = Driver::whereRaw('UPPER(REPLACE(REPLACE(name, "Ñ", "N"), "ñ", "n")) = ?', [$normalizedName])
            ->first();
        
        if ($existing) {
            return [
                'success' => false,
                'message' => "Driver '{$name}' already exists in database (ID: {$existing->id})"
            ];
        }
        
        // Generate a unique license number (placeholder - should be updated manually)
        $licenseNumber = 'N' . rand(10, 99) . '-' . rand(10000000, 99999999);
        
        // Ensure license number is unique
        while (Driver::where('license_number', $licenseNumber)->exists()) {
            $licenseNumber = 'N' . rand(10, 99) . '-' . rand(10000000, 99999999);
        }
        
        // Create driver with placeholder mobile
        $driver = Driver::create([
            'name' => strtoupper($name),
            'mobile' => '0000000000', // Placeholder - update manually
            'license_number' => $licenseNumber,
            'status' => 'available'
        ]);
        
        return [
            'success' => true,
            'driver' => $driver,
            'message' => "Driver '{$name}' imported successfully"
        ];
    }
    
    /**
     * Normalize driver name
     */
    private function normalizeName(string $name): string
    {
        $name = strtoupper($name);
        $name = str_replace(['Ñ', 'ñ'], 'N', $name);
        $name = preg_replace('/[^A-Z0-9\s\-]/', '', $name);
        return trim($name);
    }
    
    /**
     * Get list of missing drivers
     */
    public function getMissingDrivers(): array
    {
        $missing = [];
        
        foreach ($this->excelDrivers as $name) {
            $normalizedName = $this->normalizeName($name);
            
            $exists = Driver::whereRaw('UPPER(REPLACE(REPLACE(name, "Ñ", "N"), "ñ", "n")) = ?', [$normalizedName])
                ->exists();
            
            if (!$exists) {
                $missing[] = $name;
            }
        }
        
        return [
            'total_in_reference' => count($this->excelDrivers),
            'missing' => $missing,
            'missing_count' => count($missing)
        ];
    }
    
    /**
     * Update the driver reference list (for maintenance)
     */
    public function updateDriverList(array $drivers): void
    {
        $this->excelDrivers = $drivers;
    }
}
