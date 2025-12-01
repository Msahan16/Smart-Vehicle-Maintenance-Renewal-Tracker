<?php

namespace App\Livewire;

use App\Models\Vehicle;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class VehicleForm extends Component
{
    use WithFileUploads;

    public $vehicle;
    public $vehicle_number;
    public $brand;
    public $model;
    public $fuel_type;
    public $engine_capacity;
    public $manufactured_year;
    public $color;
    public $photo;
    public $license_expiry;
    public $insurance_expiry;
    public $emission_test_expiry;
    public $notes;

    public function rules()
    {
        return [
            'vehicle_number' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'fuel_type' => 'required|in:Petrol,Diesel,Electric,Hybrid,CNG,LPG',
            'engine_capacity' => 'nullable|string|max:255',
            'manufactured_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'license_expiry' => 'nullable|date',
            'insurance_expiry' => 'nullable|date',
            'emission_test_expiry' => 'nullable|date',
            'notes' => 'nullable|string',
        ];
    }

    public function mount($vehicle = null)
    {
        if ($vehicle) {
            $this->vehicle = $vehicle;
            $this->vehicle_number = $vehicle->vehicle_number;
            $this->brand = $vehicle->brand;
            $this->model = $vehicle->model;
            $this->fuel_type = $vehicle->fuel_type;
            $this->engine_capacity = $vehicle->engine_capacity;
            $this->manufactured_year = $vehicle->manufactured_year;
            $this->color = $vehicle->color;
            $this->license_expiry = $vehicle->license_expiry?->format('Y-m-d');
            $this->insurance_expiry = $vehicle->insurance_expiry?->format('Y-m-d');
            $this->emission_test_expiry = $vehicle->emission_test_expiry?->format('Y-m-d');
            $this->notes = $vehicle->notes;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'vehicle_number' => $this->vehicle_number,
            'brand' => $this->brand,
            'model' => $this->model,
            'fuel_type' => $this->fuel_type,
            'engine_capacity' => $this->engine_capacity,
            'manufactured_year' => $this->manufactured_year,
            'color' => $this->color,
            'license_expiry' => $this->license_expiry,
            'insurance_expiry' => $this->insurance_expiry,
            'emission_test_expiry' => $this->emission_test_expiry,
            'notes' => $this->notes,
        ];

        if ($this->photo) {
            $data['photo'] = $this->photo->store('vehicles', 'public');
        }

        if ($this->vehicle) {
            $this->vehicle->update($data);
            session()->flash('message', 'Vehicle updated successfully!');
        } else {
            $data['user_id'] = Auth::id();
            Vehicle::create($data);
            session()->flash('message', 'Vehicle added successfully!');
        }

        return redirect()->route('vehicles.index');
    }

    public function render()
    {
        return view('livewire.vehicle-form');
    }
}
