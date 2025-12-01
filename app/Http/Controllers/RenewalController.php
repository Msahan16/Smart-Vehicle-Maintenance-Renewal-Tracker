<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RenewalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all vehicles with upcoming renewals
        $vehicles = $user->vehicles()->get();
        
        $renewals = collect();
        
        foreach ($vehicles as $vehicle) {
            // Check License Expiry
            if ($vehicle->license_expiry) {
                $daysLeft = Carbon::parse($vehicle->license_expiry)->diffInDays(Carbon::today(), false);
                $renewals->push([
                    'vehicle' => $vehicle,
                    'type' => 'License Plate',
                    'expiry_date' => $vehicle->license_expiry,
                    'days_left' => -$daysLeft,
                    'status' => $this->getStatus(-$daysLeft),
                ]);
            }
            
            // Check Insurance Expiry
            if ($vehicle->insurance_expiry) {
                $daysLeft = Carbon::parse($vehicle->insurance_expiry)->diffInDays(Carbon::today(), false);
                $renewals->push([
                    'vehicle' => $vehicle,
                    'type' => 'Insurance',
                    'expiry_date' => $vehicle->insurance_expiry,
                    'days_left' => -$daysLeft,
                    'status' => $this->getStatus(-$daysLeft),
                ]);
            }
            
            // Check Emission Test Expiry
            if ($vehicle->emission_test_expiry) {
                $daysLeft = Carbon::parse($vehicle->emission_test_expiry)->diffInDays(Carbon::today(), false);
                $renewals->push([
                    'vehicle' => $vehicle,
                    'type' => 'Emission Test',
                    'expiry_date' => $vehicle->emission_test_expiry,
                    'days_left' => -$daysLeft,
                    'status' => $this->getStatus(-$daysLeft),
                ]);
            }
        }
        
        // Check Driver License
        if ($user->driver_license_expiry) {
            $daysLeft = Carbon::parse($user->driver_license_expiry)->diffInDays(Carbon::today(), false);
            $renewals->push([
                'vehicle' => null,
                'type' => 'Driver License',
                'expiry_date' => $user->driver_license_expiry,
                'days_left' => -$daysLeft,
                'status' => $this->getStatus(-$daysLeft),
            ]);
        }
        
        // Sort by days left (most urgent first)
        $renewals = $renewals->sortBy('days_left');
        
        return view('renewals.index', compact('renewals'));
    }
    
    private function getStatus($daysLeft)
    {
        if ($daysLeft < 0) {
            return 'overdue';
        } elseif ($daysLeft <= 7) {
            return 'critical';
        } elseif ($daysLeft <= 30) {
            return 'warning';
        } else {
            return 'safe';
        }
    }
}
