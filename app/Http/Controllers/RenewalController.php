<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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

        $html = view('emails.renewal-reminder', [
            'user' => $user,
            'reminders' => $reminders,
        ])->render();

        try {
            $response = Http::withHeaders([
                'api-key' => config('services.brevo.key'),
                'Content-Type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => config('mail.from.name'),
                    'email' => config('mail.from.address'),
                ],
                'to' => [
                    ['email' => $user->email],
                ],
                'subject' => 'Vehicle Renewal Reminder',
                'htmlContent' => $html,
            ]);

            if ($response->successful()) {
                return back()->with('success', 'Renewal reminder email sent to ' . $user->email);
            }

            return back()->with('error', 'Brevo API error: ' . $response->body());
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
