<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\MaintenanceRecord;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get total vehicles
        $totalVehicles = $user->vehicles()->count();
        
        // Get upcoming renewals
        $upcomingRenewals = collect();
        
        foreach ($user->vehicles as $vehicle) {
            if ($vehicle->license_expiry) {
                $upcomingRenewals->push((object)[
                    'vehicle' => $vehicle,
                    'type' => 'license',
                    'due_date' => $vehicle->license_expiry,
                ]);
            }
            if ($vehicle->insurance_expiry) {
                $upcomingRenewals->push((object)[
                    'vehicle' => $vehicle,
                    'type' => 'insurance',
                    'due_date' => $vehicle->insurance_expiry,
                ]);
            }
            if ($vehicle->emission_test_expiry) {
                $upcomingRenewals->push((object)[
                    'vehicle' => $vehicle,
                    'type' => 'emission',
                    'due_date' => $vehicle->emission_test_expiry,
                ]);
            }
        }
        
        // Sort by due date
        $upcomingRenewals = $upcomingRenewals->sortBy('due_date');
        
        // Calculate status counts
        $safeRenewals = 0;
        $dueSoon = 0;
        $overdue = 0;
        
        foreach ($upcomingRenewals as $renewal) {
            $daysLeft = now()->diffInDays($renewal->due_date, false);
            if ($daysLeft < 0) {
                $overdue++;
            } elseif ($daysLeft <= 30) {
                $dueSoon++;
            } else {
                $safeRenewals++;
            }
        }
        
        // Get recent maintenance
        $recentMaintenance = MaintenanceRecord::whereIn('vehicle_id', $user->vehicles->pluck('id'))
            ->orderBy('service_date', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard', compact(
            'totalVehicles',
            'safeRenewals',
            'dueSoon',
            'overdue',
            'upcomingRenewals',
            'recentMaintenance'
        ));
    }
}
