@extends('layouts.app')
@section('title', 'Vehicles')
@section('page-title', 'Impounded Vehicles')
@section('content')

<div class="page-header">
    <div>
        <div class="page-header-title">Vehicle Records</div>
        <div class="page-header-sub">Manage all impounded vehicles at Jinja Road Police Division</div>
    </div>
    @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
    <a href="{{ route('vehicles.create') }}" class="btn btn-primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Register Vehicle
    </a>
    @endif
</div>

<!-- Filters -->
<form method="GET" class="filter-bar">
    <div class="form-group">
        <label class="form-label">Search</label>
        <input type="text" name="search" class="form-control" placeholder="Plate, Case #, Owner..." value="{{ request('search') }}">
    </div>
    <div class="form-group">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="">All Statuses</option>
            @foreach(['Impounded','Pending Payment','Cleared','Released','Auctioned'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">From</label>
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
    </div>
    <div class="form-group">
        <label class="form-label">To</label>
        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
    </div>
    <div class="form-group" style="justify-content:flex-end;">
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Clear</a>
    </div>
</form>

<div class="card" style="padding:0;">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Case #</th><th>Plate</th><th>Make / Model</th>
                    <th>Owner</th><th>Impounded Date</th>
                    <th>Storage Days</th><th>Fine (UGX)</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($vehicles as $v)
            @php
                $cls = ['Impounded'=>'badge-impounded','Pending Payment'=>'badge-pending','Cleared'=>'badge-cleared','Released'=>'badge-released','Auctioned'=>'badge-auctioned'][$v->status] ?? '';
                $overdue = $v->isOverdue();
            @endphp
            <tr class="{{ $overdue ? 'overdue-row' : '' }}">
                <td><a href="{{ route('vehicles.show', $v) }}" class="text-primary fw-600">{{ $v->case_number }}</a></td>
                <td class="fw-600">{{ $v->plate_number }}</td>
                <td>{{ $v->make }} {{ $v->model }}<br><span class="text-muted" style="font-size:0.76rem;">{{ $v->color }}, {{ $v->year }}</span></td>
                <td>{{ $v->owner->full_name }}<br><span class="text-muted" style="font-size:0.76rem;">{{ $v->owner->phone }}</span></td>
                <td>{{ $v->impounded_at->format('M d, Y') }}</td>
                <td>
                    <span class="{{ $v->storageDays() > 30 ? 'text-danger fw-600' : '' }}">{{ $v->storageDays() }} days</span>
                    @if($overdue) <span class="badge badge-impounded" style="font-size:0.65rem;padding:2px 6px;">OVERDUE</span> @endif
                </td>
                <td>
                    @if($v->fine)
                        <div>{{ number_format($v->fine->total_amount) }}</div>
                        <span class="badge badge-{{ strtolower($v->fine->status) }}">{{ $v->fine->status }}</span>
                    @else —
                    @endif
                </td>
                <td><span class="badge {{ $cls }}">{{ $v->status }}</span></td>
                <td>
                    <div class="flex gap-8">
                        <a href="{{ route('vehicles.show', $v) }}" class="btn btn-secondary btn-sm">View</a>
                        @if((auth()->user()->isAdmin() || auth()->user()->isFinance()) && $v->fine && $v->fine->status !== 'Paid')
                        <a href="{{ route('payments.create', $v) }}" class="btn btn-primary btn-sm">Pay</a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="empty-state">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display:block;margin:0 auto 8px;"><path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v9"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg>
                No vehicles found.
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="pagination-wrap">{{ $vehicles->links() }}</div>
@endsection
