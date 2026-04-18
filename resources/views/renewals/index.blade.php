@extends('layouts.app')

@section('page-title', 'Renewals & Alerts')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2>Renewals & Alerts</h2>
            <p class="text-muted">Monitor all your upcoming renewals</p>
        </div>
        <form action="{{ route('renewals.send-email') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-envelope me-1"></i> Send Email Notification
            </button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card-custom">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Vehicle</th>
                        <th>Type</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Days Left</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($renewals as $renewal)
                        <tr>
                            <td>
                                @if($renewal['vehicle'])
                                    <strong>{{ $renewal['vehicle']->vehicle_number }}</strong><br>
                                    <small class="text-muted">{{ $renewal['vehicle']->brand }} {{ $renewal['vehicle']->model }}</small>
                                @else
                                    <strong>Driver License</strong><br>
                                    <small class="text-muted">{{ auth()->user()->name }}</small>
                                @endif
                            </td>
                            <td>{{ $renewal['type'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($renewal['expiry_date'])->format('M d, Y') }}</td>
                            <td>
                                @if($renewal['status'] === 'overdue')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-exclamation-circle me-1"></i>Overdue
                                    </span>
                                @elseif($renewal['status'] === 'critical')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Critical
                                    </span>
                                @elseif($renewal['status'] === 'warning')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-exclamation me-1"></i>Due Soon
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Safe
                                    </span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $daysLeft = (int) $renewal['days_left'];
                                @endphp
                                @if($daysLeft < 0)
                                    <span class="text-danger fw-bold">{{ abs($daysLeft) }} {{ abs($daysLeft) === 1 ? 'day' : 'days' }} overdue</span>
                                @else
                                    <span class="{{ $daysLeft <= 7 ? 'text-danger fw-bold' : ($daysLeft <= 30 ? 'text-warning fw-bold' : '') }}">
                                        {{ $daysLeft }} {{ $daysLeft === 1 ? 'day' : 'days' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <p class="text-muted">All renewals are up to date!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
