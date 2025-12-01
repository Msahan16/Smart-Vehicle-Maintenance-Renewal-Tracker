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

    public function documents(Vehicle $vehicle)
    {
        // Ensure user owns this vehicle
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('vehicles.documents', compact('vehicle'));
    }

    public function updateDocuments(Request $request, Vehicle $vehicle)
    {
        // Ensure user owns this vehicle
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }

        $documentType = $request->input('document_type');
        $data = [];

        switch ($documentType) {
            case 'vehicle_license':
                $validated = $request->validate([
                    'vehicle_license_front' => 'nullable|image|max:2048',
                    'vehicle_license_back' => 'nullable|image|max:2048',
                ]);

                if ($request->hasFile('vehicle_license_front')) {
                    $data['vehicle_license_front'] = $request->file('vehicle_license_front')->store('documents/vehicle_licenses', 'public');
                }
                if ($request->hasFile('vehicle_license_back')) {
                    $data['vehicle_license_back'] = $request->file('vehicle_license_back')->store('documents/vehicle_licenses', 'public');
                }
                break;

            case 'insurance':
                $validated = $request->validate([
                    'insurance_doc_front' => 'nullable|image|max:2048',
                    'insurance_doc_back' => 'nullable|image|max:2048',
                ]);

                if ($request->hasFile('insurance_doc_front')) {
                    $data['insurance_doc_front'] = $request->file('insurance_doc_front')->store('documents/insurance', 'public');
                }
                if ($request->hasFile('insurance_doc_back')) {
                    $data['insurance_doc_back'] = $request->file('insurance_doc_back')->store('documents/insurance', 'public');
                }
                break;

            case 'emission_test':
                $validated = $request->validate([
                    'emission_test_doc_front' => 'nullable|image|max:2048',
                    'emission_test_doc_back' => 'nullable|image|max:2048',
                ]);

                if ($request->hasFile('emission_test_doc_front')) {
                    $data['emission_test_doc_front'] = $request->file('emission_test_doc_front')->store('documents/emission_tests', 'public');
                }
                if ($request->hasFile('emission_test_doc_back')) {
                    $data['emission_test_doc_back'] = $request->file('emission_test_doc_back')->store('documents/emission_tests', 'public');
                }
                break;
        }

        if (!empty($data)) {
            $vehicle->update($data);
        }

        return redirect()->route('vehicles.documents', $vehicle)
            ->with('success', 'Documents updated successfully!');
    }
}
