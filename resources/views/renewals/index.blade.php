@extends('layouts.app')

@section('page-title', 'Renewals & Alerts')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Renewals & Alerts</h2>
        <p class="text-muted">Monitor all your upcoming renewals</p>
    </div>
</div>

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
                                @if($renewal['days_left'] < 0)
                                    <span class="text-danger fw-bold">{{ abs($renewal['days_left']) }} days overdue</span>
                                @else
                                    <span class="{{ $renewal['days_left'] <= 7 ? 'text-danger fw-bold' : ($renewal['days_left'] <= 30 ? 'text-warning fw-bold' : '') }}">
                                        {{ $renewal['days_left'] }} days
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
