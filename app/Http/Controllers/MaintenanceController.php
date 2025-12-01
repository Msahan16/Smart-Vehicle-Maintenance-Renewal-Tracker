<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRecord;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenanceRecords = MaintenanceRecord::whereHas('vehicle', function ($query) {
            $query->where('user_id', Auth::id());
        })->with('vehicle')->latest()->paginate(15);

        return view('maintenance.index', compact('maintenanceRecords'));
    }

    public function create()
    {
        $vehicles = Auth::user()->vehicles;
        return view('maintenance.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'service_type' => 'required|string|max:255',
            'service_date' => 'required|date',
            'next_service_date' => 'nullable|date|after:service_date',
            'mileage' => 'nullable|integer|min:0',
            'cost' => 'nullable|numeric|min:0',
            'service_center' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'service_bill' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'related_document' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Verify vehicle belongs to user
        $vehicle = Vehicle::where('id', $validated['vehicle_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Map next_service_date to next_due_date for database
        if (isset($validated['next_service_date'])) {
            $validated['next_due_date'] = $validated['next_service_date'];
            unset($validated['next_service_date']);
        }

        // Handle service bill upload
        if ($request->hasFile('service_bill')) {
            $validated['service_bill'] = $request->file('service_bill')->store('documents/service_bills', 'public');
        }

        // Handle related document upload
        if ($request->hasFile('related_document')) {
            $validated['related_document'] = $request->file('related_document')->store('documents/maintenance_docs', 'public');
        }

        MaintenanceRecord::create($validated);

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance record added successfully!');
    }

    public function edit(MaintenanceRecord $maintenance)
    {
        // Ensure user owns this maintenance record
        if ($maintenance->vehicle->user_id !== Auth::id()) {
            abort(403);
        }

        $vehicles = Auth::user()->vehicles;
        return view('maintenance.edit', compact('maintenance', 'vehicles'));
    }

    public function update(Request $request, MaintenanceRecord $maintenance)
    {
        // Ensure user owns this maintenance record
        if ($maintenance->vehicle->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'service_type' => 'required|string|max:255',
            'service_date' => 'required|date',
            'next_service_date' => 'nullable|date|after:service_date',
            'mileage' => 'nullable|integer|min:0',
            'cost' => 'nullable|numeric|min:0',
            'service_center' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'service_bill' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'related_document' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Verify new vehicle belongs to user
        $vehicle = Vehicle::where('id', $validated['vehicle_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Map next_service_date to next_due_date for database
        if (isset($validated['next_service_date'])) {
            $validated['next_due_date'] = $validated['next_service_date'];
            unset($validated['next_service_date']);
        }

        // Handle service bill upload
        if ($request->hasFile('service_bill')) {
            // Delete old file if exists
            if ($maintenance->service_bill) {
                Storage::disk('public')->delete($maintenance->service_bill);
            }
            $validated['service_bill'] = $request->file('service_bill')->store('documents/service_bills', 'public');
        }

        // Handle related document upload
        if ($request->hasFile('related_document')) {
            // Delete old file if exists
            if ($maintenance->related_document) {
                Storage::disk('public')->delete($maintenance->related_document);
            }
            $validated['related_document'] = $request->file('related_document')->store('documents/maintenance_docs', 'public');
        }

        $maintenance->update($validated);

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance record updated successfully!');
    }

    public function destroy(MaintenanceRecord $maintenance)
    {
        // Ensure user owns this maintenance record
        if ($maintenance->vehicle->user_id !== Auth::id()) {
            abort(403);
        }

        $maintenance->delete();

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance record deleted successfully!');
    }
}
