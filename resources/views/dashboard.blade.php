@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-1">Welcome back, {{ auth()->user()->name }}! 👋</h2>
        <p class="text-muted">Here's an overview of your vehicle maintenance status</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card primary">
            <div class="icon">
                <i class="fas fa-car"></i>
            </div>
            <h3>{{ $totalVehicles }}</h3>
            <p>Total Vehicles</p>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card success">
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3>{{ $safeRenewals }}</h3>
            <p>Safe Renewals</p>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card warning">
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3>{{ $dueSoon }}</h3>
            <p>Due Soon</p>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card danger">
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <h3>{{ $overdue }}</h3>
            <p>Overdue</p>
        </div>
    </div>
</div>

<div class="row">
    <!-- Upcoming Renewals -->
    <div class="col-lg-8 mb-4">
        <div class="card-custom">
            <div class="card-header">
                <i class="fas fa-bell me-2"></i>Upcoming Renewals & Alerts
            </div>
            <div class="card-body">
                @if($upcomingRenewals->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Type</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingRenewals as $renewal)
                                    <tr>
                                        <td>
                                            @if($renewal->vehicle)
                                                <strong>{{ $renewal->vehicle->brand }} {{ $renewal->vehicle->model }}</strong><br>
                                                <small class="text-muted">{{ $renewal->vehicle->vehicle_number }}</small>
                                            @else
                                                <strong>{{ auth()->user()->name }}</strong><br>
                                                <small class="text-muted">{{ auth()->user()->driver_license_number ?? 'Driver License' }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <i class="fas fa-{{ $renewal->type == 'Insurance' ? 'shield-alt' : ($renewal->type == 'Vehicle License' ? 'id-card' : ($renewal->type == 'Driver License' ? 'id-badge' : 'cloud')) }} me-1"></i>
                                            {{ $renewal->type }}
                                        </td>
                                        <td>{{ $renewal->due_date->format('M d, Y') }}</td>
                                        <td>
                                            @php
                                                $daysLeft = now()->diffInDays($renewal->due_date, false);
                                                $badgeClass = $daysLeft < 0 ? 'overdue' : ($daysLeft <= 7 ? 'overdue' : ($daysLeft <= 30 ? 'due-soon' : 'safe'));
                                                $statusText = $daysLeft < 0 ? 'Overdue' : ($daysLeft <= 7 ? $daysLeft . ' days left' : ($daysLeft <= 30 ? $daysLeft . ' days left' : 'Safe'));
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p class="text-muted">All renewals are up to date!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Maintenance -->
    <div class="col-lg-4 mb-4">
        <div class="card-custom">
            <div class="card-header">
                <i class="fas fa-wrench me-2"></i>Recent Maintenance
            </div>
            <div class="card-body">
                @if($recentMaintenance->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentMaintenance as $maintenance)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $maintenance->service_type }}</h6>
                                        <small class="text-muted">
                                            {{ $maintenance->vehicle->brand }} {{ $maintenance->vehicle->model }}
                                        </small>
                                    </div>
                                    <small class="text-muted">{{ $maintenance->service_date->diffForHumans() }}</small>
                                </div>
                                @if($maintenance->next_due_date)
                                    <small class="text-primary">
                                        <i class="fas fa-calendar me-1"></i>
                                        Next: {{ $maintenance->next_due_date->format('M d, Y') }}
                                    </small>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-tools fa-2x text-muted mb-3"></i>
                        <p class="text-muted">No maintenance records yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card-custom mt-4">
            <div class="card-header">
                <i class="fas fa-bolt me-2"></i>Quick Actions
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('vehicles.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Vehicle
                    </a>
                    <a href="{{ route('maintenance.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-wrench me-2"></i>Log Maintenance
                    </a>
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
