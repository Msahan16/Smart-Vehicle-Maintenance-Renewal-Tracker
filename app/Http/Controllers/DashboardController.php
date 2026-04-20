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
        $vehicles = $user->vehicles()->with('maintenanceRecords')->get();
        $vehicleIds = $vehicles->pluck('id');
        
        // Get total vehicles
        $totalVehicles = $vehicles->count();
        
        // Get upcoming renewals
        $upcomingRenewals = collect();
        
        foreach ($vehicles as $vehicle) {
            if ($vehicle->license_expiry) {
                $upcomingRenewals->push((object)[
                    'vehicle' => $vehicle,
                    'type' => 'Vehicle License',
                    'due_date' => $vehicle->license_expiry,
                ]);
            }
            if ($vehicle->insurance_expiry) {
                $upcomingRenewals->push((object)[
                    'vehicle' => $vehicle,
                    'type' => 'Insurance',
                    'due_date' => $vehicle->insurance_expiry,
                ]);
            }
            if ($vehicle->emission_test_expiry) {
                $upcomingRenewals->push((object)[
                    'vehicle' => $vehicle,
                    'type' => 'Emission Test',
                    'due_date' => $vehicle->emission_test_expiry,
                ]);
            }
        }
        
        // Add driver license expiry
        if ($user->driver_license_expiry) {
            $upcomingRenewals->push((object)[
                'vehicle' => null,
                'type' => 'Driver License',
                'due_date' => $user->driver_license_expiry,
            ]);
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
        $recentMaintenance = MaintenanceRecord::whereIn('vehicle_id', $vehicleIds)
            ->with('vehicle')
            ->orderBy('service_date', 'desc')
            ->limit(5)
            ->get();

        // AI-style condition score based on maintenance history per vehicle
        $aiVehicleConditions = $vehicles->map(function ($vehicle) {
            return (object) [
                'vehicle' => $vehicle,
                'analysis' => $this->analyzeVehicleCondition($vehicle),
            ];
        })->sortByDesc(function ($item) {
            return $item->analysis['score'];
        })->values();
        
        return view('dashboard', compact(
            'totalVehicles',
            'safeRenewals',
            'dueSoon',
            'overdue',
            'upcomingRenewals',
            'recentMaintenance',
            'aiVehicleConditions'
        ));
    }

    private function analyzeVehicleCondition(Vehicle $vehicle): array
    {
        $records = $vehicle->maintenanceRecords->sortByDesc('service_date')->values();

        if ($records->isEmpty()) {
            return [
                'score' => 35,
                'label' => 'Insufficient Data',
                'badge' => 'due-soon',
                'summary' => 'No maintenance records found. Add service history for a better condition estimate.',
            ];
        }

        $score = 50;
        $latestService = $records->first();
        $daysSinceService = (int) now()->diffInDays($latestService->service_date, false) * -1;

        if ($daysSinceService <= 120) {
            $score += 20;
        } elseif ($daysSinceService <= 240) {
            $score += 10;
        } elseif ($daysSinceService > 365) {
            $score -= 30;
        } else {
            $score -= 15;
        }

        $nextDueRecord = $records
            ->filter(fn ($record) => ! is_null($record->next_due_date))
            ->sortBy('next_due_date')
            ->first();

        if ($nextDueRecord) {
            $daysToNextDue = now()->diffInDays($nextDueRecord->next_due_date, false);

            if ($daysToNextDue < 0) {
                $score -= 40;
            } elseif ($daysToNextDue <= 15) {
                $score -= 25;
            } elseif ($daysToNextDue <= 30) {
                $score -= 15;
            } elseif ($daysToNextDue <= 60) {
                $score -= 5;
            } else {
                $score += 10;
            }
        }

        $recordsLastYear = $records->filter(function ($record) {
            return $record->service_date && $record->service_date->greaterThanOrEqualTo(now()->subYear());
        })->count();

        if ($recordsLastYear >= 3) {
            $score += 20;
        } elseif ($recordsLastYear === 2) {
            $score += 10;
        } elseif ($recordsLastYear === 0) {
            $score -= 20;
        }

        $score = max(0, min(100, $score));

        if ($score >= 80) {
            return [
                'score' => $score,
                'label' => 'Excellent',
                'badge' => 'safe',
                'summary' => 'Maintenance history is strong and upcoming service timing looks healthy.',
            ];
        }

        if ($score >= 60) {
            return [
                'score' => $score,
                'label' => 'Good',
                'badge' => 'safe',
                'summary' => 'Condition appears stable, but keep following upcoming service dates.',
            ];
        }

        if ($score >= 40) {
            return [
                'score' => $score,
                'label' => 'Fair',
                'badge' => 'due-soon',
                'summary' => 'Some maintenance gaps detected. Schedule the next service soon.',
            ];
        }

        return [
            'score' => $score,
            'label' => 'Needs Attention',
            'badge' => 'overdue',
            'summary' => 'Overdue or infrequent maintenance suggests increased risk of issues.',
        ];
    }
}
