@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('content')

<div class="stats-grid">
    <div class="stat-card">
        <div>
            <div class="stat-label">Total Impounded</div>
            <div class="stat-value">{{ number_format($stats['total_impounded']) }}</div>
            <div class="stat-sub">Currently in yard</div>
        </div>
        <div class="stat-icon blue">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v9a2 2 0 01-2 2h-2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">Pending Payment</div>
            <div class="stat-value">{{ number_format($stats['pending_payment']) }}</div>
            <div class="stat-sub">Awaiting fine payment</div>
        </div>
        <div class="stat-icon orange">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 100 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">Released Today</div>
            <div class="stat-value">{{ number_format($stats['released']) }}</div>
            <div class="stat-sub">Total released vehicles</div>
        </div>
        <div class="stat-icon green">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">Overdue Vehicles</div>
            <div class="stat-value text-danger">{{ number_format($stats['overdue_vehicles']) }}</div>
            <div class="stat-sub">Held > 30 days</div>
        </div>
        <div class="stat-icon red">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">Revenue Today</div>
            <div class="stat-value text-success">UGX {{ number_format($stats['revenue_today']) }}</div>
            <div class="stat-sub">Collected today</div>
        </div>
        <div class="stat-icon teal">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">Monthly Revenue</div>
            <div class="stat-value text-primary">UGX {{ number_format($stats['revenue_month']) }}</div>
            <div class="stat-sub">{{ now()->format('F Y') }}</div>
        </div>
        <div class="stat-icon blue">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="2" x2="12" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">UGX {{ number_format($stats['revenue_total']) }}</div>
            <div class="stat-sub">All time collected</div>
        </div>
        <div class="stat-icon green">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">Outstanding Fines</div>
            <div class="stat-value text-danger">UGX {{ number_format($stats['outstanding_fines']) }}</div>
            <div class="stat-sub">Unpaid / partial</div>
        </div>
        <div class="stat-icon red">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 21H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v7M14 21l4-4 4 4M18 17v8"/></svg>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- Revenue Chart -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Revenue Trend (Last 6 Months)</div>
        </div>
        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Vehicle Status Overview</div>
        </div>
        <div class="chart-container">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- Recent Vehicles -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Recently Impounded</div>
            @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
            <a href="{{ route('vehicles.create') }}" class="btn btn-primary btn-sm">+ New Record</a>
            @endif
        </div>
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Case #</th><th>Plate</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                @forelse($recentVehicles as $v)
                <tr>
                    <td><a href="{{ route('vehicles.show', $v) }}" class="text-primary fw-600">{{ $v->case_number }}</a></td>
                    <td class="fw-600">{{ $v->plate_number }}</td>
                    <td>
                        @php
                            $cls = ['Impounded'=>'badge-impounded','Pending Payment'=>'badge-pending','Cleared'=>'badge-cleared','Released'=>'badge-released','Auctioned'=>'badge-auctioned'][$v->status] ?? '';
                        @endphp
                        <span class="badge {{ $cls }}">{{ $v->status }}</span>
                    </td>
                    <td class="text-muted">{{ $v->impounded_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted" style="padding:24px;">No vehicles recorded</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Recent Activity</div>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('audit.index') }}" class="btn btn-secondary btn-sm">View All</a>
            @endif
        </div>
        <div class="timeline" style="max-height:340px;overflow-y:auto;">
            @forelse($recentLogs as $log)
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-time">{{ $log->created_at->diffForHumans() }} — {{ $log->user?->name ?? 'System' }}</div>
                <div class="timeline-content">{{ ucwords(str_replace('.',' ', $log->action)) }}</div>
            </div>
            @empty
            <p class="text-muted">No recent activity</p>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
// Revenue Chart
const revLabels = @json($monthlyRevenue->pluck('label'));
const revData   = @json($monthlyRevenue->pluck('total'));
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: revLabels,
        datasets: [{
            label: 'Revenue (UGX)',
            data: revData,
            backgroundColor: 'rgba(21,101,192,0.18)',
            borderColor: '#1565C0',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}} }
});

// Status Chart
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Impounded','Pending Payment','Cleared','Released','Auctioned'],
        datasets: [{
            data: [{{ $stats['total_impounded'] }},{{ $stats['pending_payment'] }},{{ $stats['cleared'] }},{{ $stats['released'] }},{{ $stats['auctioned'] }}],
            backgroundColor:['#FF6D00','#283593','#1B5E20','#0D47A1','#880E4F'],
            borderWidth: 0,
        }]
    },
    options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{position:'bottom'}} }
});
</script>
@endpush
@endsection
