<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoDriverController extends Controller
{
    /**
     * Display all co-driver relationships
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Get all drivers with their co-drivers
        $driversQuery = Driver::query();
        
        if ($search) {
            $driversQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('license_number', 'like', '%' . $search . '%')
                      ->orWhere('mobile', 'like', '%' . $search . '%');
            });
        }
        
        $drivers = $driversQuery->with(['coDrivers', 'driversHavingAsCoDriver'])
            ->orderBy('name')
            ->paginate(7);
        
        // Get all available drivers for the modal
        $availableDrivers = Driver::orderBy('name')->get();
        
        return view('dispatch.co-drivers.index', compact('drivers', 'availableDrivers', 'search'));
    }
    
    /**
     * Assign co-driver relationship via modal
     */
    public function assign(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'co_driver_id' => 'required|exists:drivers,id',
        ]);
        
        $driverId = $validated['driver_id'];
        $coDriverId = $validated['co_driver_id'];
        
        // Cannot be co-driver with themselves
        if ($driverId == $coDriverId) {
            return redirect()
                ->back()
                ->with('error', 'A driver cannot be a co-driver with themselves.');
        }
        
        $driver = Driver::findOrFail($driverId);
        $coDriver = Driver::findOrFail($coDriverId);
        
        // Check if already co-drivers
        if ($driver->hasCoDriver($coDriverId)) {
            return redirect()
                ->back()
                ->with('error', $driver->name . ' and ' . $coDriver->name . ' are already co-drivers.');
        }
        
        // Add co-driver relationship
        $driver->coDrivers()->attach($coDriverId);
        
        return redirect()
            ->back()
            ->with('success', $coDriver->name . ' has been assigned as co-driver to ' . $driver->name . '.');
    }
    
    /**
     * Remove co-driver relationship
     */
    public function remove(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'co_driver_id' => 'required|exists:drivers,id',
        ]);
        
        $driver = Driver::findOrFail($validated['driver_id']);
        $coDriver = Driver::findOrFail($validated['co_driver_id']);
        
        // Remove the relationship from both directions
        $driver->coDrivers()->detach($coDriver->id);
        $driver->driversHavingAsCoDriver()->detach($coDriver->id);
        
        return redirect()
            ->back()
            ->with('success', 'Co-driver relationship removed between ' . $driver->name . ' and ' . $coDriver->name . '.');
    }
    
    /**
     * Get co-driver stats
     */
    public function stats()
    {
        $totalDrivers = Driver::count();
        $driversWithCoDrivers = Driver::has('coDrivers')->orHas('driversHavingAsCoDriver')->distinct()->count();
        $totalRelationships = DB::table('co_drivers')->count();
        
        return response()->json([
            'total_drivers' => $totalDrivers,
            'drivers_with_co_drivers' => $driversWithCoDrivers,
            'total_relationships' => $totalRelationships,
        ]);
    }
}
