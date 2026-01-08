<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DriverImportService;

class ImportDriversFromExcel extends Command
{
    protected $signature = 'drivers:import {--check : Only check for missing drivers without importing}';
    protected $description = 'Import drivers from Excel reference list (ALANG, CANETE, INTRUZO, LAURENTE, RIVERA, SERENO, TOCMO)';

    public function handle()
    {
        $service = new DriverImportService();
        
        if ($this->option('check')) {
            $this->info('Checking for missing drivers from Excel reference...');
            $result = $service->getMissingDrivers();
            
            $this->info("Total drivers in reference: {$result['total_in_reference']}");
            $this->info("Missing in database: {$result['missing_count']}");
            
            if ($result['missing_count'] > 0) {
                $this->warn("\nMissing drivers:");
                foreach ($result['missing'] as $driver) {
                    $this->line("  - $driver");
                }
            } else {
                $this->info('All drivers from Excel reference are already in the database!');
            }
            
            return 0;
        }
        
        if (!$this->confirm('This will import missing drivers from Excel reference. Continue?')) {
            $this->info('Import cancelled.');
            return 0;
        }
        
        $this->info('Importing drivers...');
        $result = $service->importMissingDrivers();
        
        $this->info("Imported: {$result['imported']} drivers");
        $this->info("Skipped: {$result['skipped']} drivers (already exist)");
        
        if (count($result['errors']) > 0) {
            $this->warn("\nSkipped drivers:");
            foreach ($result['errors'] as $error) {
                $this->line("  $error");
            }
        }
        
        if ($result['imported'] > 0) {
            $this->warn("\n?? Note: Imported drivers have placeholder mobile numbers (0000000000).");
            $this->warn("   Please update them with actual contact information.");
        }
        
        $this->info('Import completed!');
        return 0;
    }
}
