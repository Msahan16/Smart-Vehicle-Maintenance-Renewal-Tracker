<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Auth::user()->vehicles()->with('maintenanceRecords')->latest()->get();
        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        // This is handled by Livewire VehicleForm component
        return redirect()->route('vehicles.index');
    }

    public function edit(Vehicle $vehicle)
    {
        // Ensure user owns this vehicle
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        // This is handled by Livewire VehicleForm component
        return redirect()->route('vehicles.index');
    }

    public function destroy(Vehicle $vehicle)
    {
        // Ensure user owns this vehicle
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }
        
        $vehicle->delete();
        
        return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted successfully!');
    }
}
