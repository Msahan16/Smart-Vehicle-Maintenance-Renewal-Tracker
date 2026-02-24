<?php

namespace App\Http\Controllers;

use App\Mail\RenewalReminderMail;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
                    'type' => 'Vehicle License',
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
    
    public function sendEmail()
    {
        $user = Auth::user();
        $vehicles = $user->vehicles()->get();
        $reminders = [];

        foreach ($vehicles as $vehicle) {
            if ($vehicle->license_expiry) {
                $daysLeft = (int) Carbon::today()->diffInDays($vehicle->license_expiry, false);
                $reminders[] = [
                    'type' => 'Vehicle License',
                    'vehicle' => $vehicle->brand . ' ' . $vehicle->model,
                    'vehicle_number' => $vehicle->vehicle_number,
                    'due_date' => Carbon::parse($vehicle->license_expiry)->format('M d, Y'),
                    'days_left' => $daysLeft,
                ];
            }
            if ($vehicle->insurance_expiry) {
                $daysLeft = (int) Carbon::today()->diffInDays($vehicle->insurance_expiry, false);
                $reminders[] = [
                    'type' => 'Insurance',
                    'vehicle' => $vehicle->brand . ' ' . $vehicle->model,
                    'vehicle_number' => $vehicle->vehicle_number,
                    'due_date' => Carbon::parse($vehicle->insurance_expiry)->format('M d, Y'),
                    'days_left' => $daysLeft,
                ];
            }
            if ($vehicle->emission_test_expiry) {
                $daysLeft = (int) Carbon::today()->diffInDays($vehicle->emission_test_expiry, false);
                $reminders[] = [
                    'type' => 'Emission Test',
                    'vehicle' => $vehicle->brand . ' ' . $vehicle->model,
                    'vehicle_number' => $vehicle->vehicle_number,
                    'due_date' => Carbon::parse($vehicle->emission_test_expiry)->format('M d, Y'),
                    'days_left' => $daysLeft,
                ];
            }
        }

        if ($user->driver_license_expiry) {
            $daysLeft = (int) Carbon::today()->diffInDays($user->driver_license_expiry, false);
            $reminders[] = [
                'type' => 'Driver License',
                'vehicle' => 'N/A',
                'vehicle_number' => $user->driver_license_number ?? 'N/A',
                'due_date' => Carbon::parse($user->driver_license_expiry)->format('M d, Y'),
                'days_left' => $daysLeft,
            ];
        }

        if (empty($reminders)) {
            return back()->with('info', 'No renewal data found to send.');
        }

        try {
            Mail::to($user->email)->send(new RenewalReminderMail($user, $reminders));
            return back()->with('success', 'Renewal reminder email sent to ' . $user->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
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
