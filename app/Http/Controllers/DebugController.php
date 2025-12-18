<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DebugController extends Controller
{
    public function testDriverCreation()
    {
        $results = [];
        
        // Test 1: Database Connection
        try {
            DB::connection()->getPdo();
            $results['database'] = '✓ Connected';
        } catch (\Exception $e) {
            $results['database'] = '✗ Error: ' . $e->getMessage();
        }
        
        // Test 2: Drivers Table
        try {
            $count = Driver::count();
            $results['drivers_table'] = "✓ Exists with $count drivers";
        } catch (\Exception $e) {
            $results['drivers_table'] = '✗ Error: ' . $e->getMessage();
        }
        
        // Test 3: Session Config
        $results['session_config'] = [
            'driver' => config('session.driver'),
            'lifetime' => config('session.lifetime'),
            'secure' => config('session.secure'),
            'same_site' => config('session.same_site'),
            'domain' => config('session.domain'),
        ];
        
        // Test 4: CSRF Token
        $results['csrf_token'] = csrf_token();
        
        // Test 5: Try to create driver
        try {
            $testDriver = Driver::create([
                'name' => 'Test Driver ' . time(),
                'mobile' => '09' . rand(100000000, 999999999),
                'license_number' => 'TEST' . rand(1000, 9999),
                'status' => 'available'
            ]);
            $results['driver_creation'] = '✓ Success! ID: ' . $testDriver->id;
            $testDriver->delete();
            $results['driver_cleanup'] = '✓ Test driver deleted';
        } catch (\Exception $e) {
            $results['driver_creation'] = '✗ Error: ' . $e->getMessage();
            Log::error('Driver creation test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        return response()->json($results, 200, [], JSON_PRETTY_PRINT);
    }
    
    public function testAjaxDriverCreation(Request $request)
    {
        $results = [];
        
        // Test CSRF token
        $results['csrf_token_present'] = $request->header('X-CSRF-TOKEN') ? 'Yes' : 'No';
        $results['session_token'] = $request->session()->token();
        
        // Validate data
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'mobile' => 'required|string|max:20',
                'license_number' => 'required|string|max:50|unique:drivers,license_number',
                'status' => 'sometimes|in:available,on-trip,off-duty',
            ]);
            $results['validation'] = '✓ Passed';
        } catch (\Exception $e) {
            $results['validation'] = '✗ Failed: ' . $e->getMessage();
            return response()->json($results, 422);
        }
        
        // Try to create
        try {
            $driver = Driver::create($validated);
            $results['creation'] = '✓ Success! ID: ' . $driver->id;
            return response()->json($results);
        } catch (\Exception $e) {
            $results['creation'] = '✗ Failed: ' . $e->getMessage();
            return response()->json($results, 500);
        }
    }
}
