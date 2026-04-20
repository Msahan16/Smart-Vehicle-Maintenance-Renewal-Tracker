@extends('layouts.app')

@section('page-title', 'Expense Analytics')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-1">Expense Analytics</h2>
        <p class="text-muted">Track maintenance spending trends and highest cost areas.</p>
    </div>
</div>

<div class="row mb-4 g-3">
    <div class="col-6 col-lg-3">
        <div class="p-3 rounded border bg-white h-100">
            <small class="text-muted d-block mb-1">Total Spend</small>
            <h5 class="mb-0">{{ $currencySymbol }}{{ number_format($totalMaintenanceCost, 2) }}</h5>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="p-3 rounded border bg-white h-100">
            <small class="text-muted d-block mb-1">Last 30 Days</small>
            <h5 class="mb-0">{{ $currencySymbol }}{{ number_format($last30DaysMaintenanceCost, 2) }}</h5>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="p-3 rounded border bg-white h-100">
            <small class="text-muted d-block mb-1">Avg Per Service</small>
            <h5 class="mb-0">{{ $currencySymbol }}{{ number_format($averageMaintenanceCost, 2) }}</h5>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="p-3 rounded border bg-white h-100">
            <small class="text-muted d-block mb-1">Highest Month</small>
            <h5 class="mb-0">
                @if($peakExpenseMonth && $peakExpenseMonth['total'] > 0)
                    {{ $peakExpenseMonth['label'] }}
                @else
                    N/A
                @endif
            </h5>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span><i class="fas fa-chart-bar me-2"></i>Expense Breakdown</span>
                <small class="text-muted">Based on maintenance records with cost values</small>
            </div>
            <div class="card-body">
                @if($expenseRecordCount > 0)
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <h6 class="mb-3">Monthly Spend Trend (6 Months)</h6>
                            @foreach($monthlyExpenseTrend as $month)
                                @php
                                    $percentage = $maxMonthlyExpense > 0 ? ($month['total'] / $maxMonthlyExpense) * 100 : 0;
                                @endphp
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>{{ $month['label'] }}</span>
                                        <strong>{{ $currencySymbol }}{{ number_format($month['total'], 2) }}</strong>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        <div
                                            class="progress-bar bg-primary"
                                            role="progressbar"
                                            style="width: {{ $percentage }}%;"
                                            aria-valuenow="{{ (int) $percentage }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                        ></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="col-lg-6">
                            <h6 class="mb-3">Top Service Cost Categories</h6>
                            @if($serviceTypeExpenseBreakdown->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle">
                                        <thead>
                                            <tr>
                                                <th>Service Type</th>
                                                <th class="text-end">Records</th>
                                                <th class="text-end">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($serviceTypeExpenseBreakdown as $serviceType)
                                                <tr>
                                                    <td>{{ $serviceType->service_type }}</td>
                                                    <td class="text-end">{{ $serviceType->record_count }}</td>
                                                    <td class="text-end">{{ $currencySymbol }}{{ number_format((float) $serviceType->total_cost, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted mb-0">No categorized expenses available yet.</p>
                            @endif
                        </div>
                    </div>

                    <hr class="my-4">

                    <div>
                        <h6 class="mb-3">Top Vehicles by Maintenance Cost</h6>
                        @if($vehicleExpenseBreakdown->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Vehicle</th>
                                            <th class="text-end">Service Entries</th>
                                            <th class="text-end">Total Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vehicleExpenseBreakdown as $vehicleExpense)
                                            <tr>
                                                <td>
                                                    @if($vehicleExpense->vehicle)
                                                        <strong>{{ $vehicleExpense->vehicle->brand }} {{ $vehicleExpense->vehicle->model }}</strong><br>
                                                        <small class="text-muted">{{ $vehicleExpense->vehicle->vehicle_number }}</small>
                                                    @else
                                                        <span class="text-muted">Vehicle removed</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">{{ $vehicleExpense->record_count }}</td>
                                                <td class="text-end fw-semibold">{{ $currencySymbol }}{{ number_format((float) $vehicleExpense->total_cost, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted mb-0">No vehicle expense data available yet.</p>
                        @endif
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-receipt fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Log maintenance costs to unlock expense analytics.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
