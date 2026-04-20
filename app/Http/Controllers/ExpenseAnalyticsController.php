<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRecord;
use Illuminate\Support\Facades\Auth;

class ExpenseAnalyticsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vehicles = $user->vehicles()->get();
        $vehicleIds = $vehicles->pluck('id');

        $maintenanceExpenseQuery = MaintenanceRecord::whereIn('vehicle_id', $vehicleIds)
            ->whereNotNull('cost');

        $totalMaintenanceCost = (float) (clone $maintenanceExpenseQuery)->sum('cost');
        $last30DaysMaintenanceCost = (float) (clone $maintenanceExpenseQuery)
            ->whereDate('service_date', '>=', now()->subDays(30))
            ->sum('cost');
        $expenseRecordCount = (int) (clone $maintenanceExpenseQuery)->count();
        $averageMaintenanceCost = $expenseRecordCount > 0
            ? round($totalMaintenanceCost / $expenseRecordCount, 2)
            : 0.0;

        $monthlyExpenseTrend = collect(range(0, 5))->map(function (int $offset) use ($maintenanceExpenseQuery) {
            $month = now()->subMonths(5 - $offset)->startOfMonth();
            $monthTotal = (float) (clone $maintenanceExpenseQuery)
                ->whereBetween('service_date', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
                ->sum('cost');

            return [
                'label' => $month->format('M Y'),
                'total' => round($monthTotal, 2),
            ];
        });

        $maxMonthlyExpense = (float) $monthlyExpenseTrend->max('total');
        $peakExpenseMonth = $monthlyExpenseTrend->sortByDesc('total')->first();

        $serviceTypeExpenseBreakdown = (clone $maintenanceExpenseQuery)
            ->selectRaw('service_type, SUM(cost) as total_cost, COUNT(*) as record_count')
            ->groupBy('service_type')
            ->orderByDesc('total_cost')
            ->limit(5)
            ->get();

        $vehicleExpenseBreakdown = (clone $maintenanceExpenseQuery)
            ->selectRaw('vehicle_id, SUM(cost) as total_cost, COUNT(*) as record_count')
            ->with('vehicle:id,brand,model,vehicle_number')
            ->groupBy('vehicle_id')
            ->orderByDesc('total_cost')
            ->limit(5)
            ->get();

        $currencySymbol = (string) config('app.currency_symbol');

        return view('expenses.index', compact(
            'currencySymbol',
            'totalMaintenanceCost',
            'last30DaysMaintenanceCost',
            'averageMaintenanceCost',
            'expenseRecordCount',
            'monthlyExpenseTrend',
            'maxMonthlyExpense',
            'peakExpenseMonth',
            'serviceTypeExpenseBreakdown',
            'vehicleExpenseBreakdown'
        ));
    }
}