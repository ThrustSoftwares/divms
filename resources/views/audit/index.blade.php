@extends('layouts.app')
@section('title', 'Audit Trail')
@section('page-title', 'Audit Trail')
@section('content')

<div class="page-header">
    <div class="page-header-title">System Audit Trail</div>
    <div class="text-muted">Complete log of all system activities</div>
</div>

<form method="GET" class="filter-bar">
    <div class="form-group">
        <label class="form-label">Action Contains</label>
        <input type="text" name="action" class="form-control" placeholder="e.g. vehicle.created" value="{{ request('action') }}">
    </div>
    <div class="form-group">
        <label class="form-label">Date From</label>
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
    </div>
    <div class="form-group">
        <label class="form-label">Date To</label>
        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
    </div>
    <div class="form-group" style="justify-content:flex-end;">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('audit.index') }}" class="btn btn-secondary">Clear</a>
    </div>
</form>

<div class="card" style="padding:0;">
    <div class="table-wrapper">
        <table>
            <thead><tr><th>Timestamp</th><th>User</th><th>Action</th><th>Model</th><th>IP Address</th></tr></thead>
            <tbody>
            @forelse($logs as $log)
            <tr>
                <td style="white-space:nowrap;">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                <td>
                    @if($log->user)
                        <div class="fw-600" style="font-size:0.85rem;">{{ $log->user->name }}</div>
                        <div class="text-muted" style="font-size:0.75rem;">{{ $log->user->role->display_name }}</div>
                    @else
                        <span class="text-muted">System</span>
                    @endif
                </td>
                <td>
                    <span style="background:var(--primary-50);color:var(--primary);padding:3px 8px;border-radius:5px;font-size:0.76rem;font-family:monospace;font-weight:600;">{{ $log->action }}</span>
                </td>
                <td>
                    @if($log->model_type)
                        <div style="font-size:0.78rem;color:var(--text-muted);">{{ class_basename($log->model_type) }} #{{ $log->model_id }}</div>
                    @else —
                    @endif
                </td>
                <td class="text-muted" style="font-size:0.82rem;">{{ $log->ip_address ?? '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="empty-state">No audit logs found</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="pagination-wrap">{{ $logs->links() }}</div>
@endsection
