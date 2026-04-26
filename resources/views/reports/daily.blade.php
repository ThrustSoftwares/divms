@extends('layouts.app')
@section('title', "Daily Report - $date")
@section('page-title', 'Daily Report')
@section('content')

<div class="page-header">
    <div class="page-header-title">Daily Report — {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</div>
    <div class="flex gap-8">
        <button onclick="window.print()" class="btn btn-primary no-print">🖨️ Print</button>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary no-print">← Back</a>
    </div>
</div>

<form method="GET" class="filter-bar no-print">
    <div class="form-group">
        <label class="form-label">Select Date</label>
        <input type="date" name="date" class="form-control" value="{{ $date }}">
    </div>
    <button type="submit" class="btn btn-primary" style="align-self:flex-end;">Generate</button>
</form>

<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);">
    <div class="stat-card"><div><div class="stat-label">Impounded</div><div class="stat-value">{{ $impounded->count() }}</div></div><div class="stat-icon blue"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v9a2 2 0 01-2 2h-2"/></svg></div></div>
    <div class="stat-card"><div><div class="stat-label">Released</div><div class="stat-value">{{ $released->count() }}</div></div><div class="stat-icon green"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div></div>
    <div class="stat-card"><div><div class="stat-label">Payments</div><div class="stat-value">{{ $payments->count() }}</div></div><div class="stat-icon orange"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/></svg></div></div>
    <div class="stat-card"><div><div class="stat-label">Revenue</div><div class="stat-value text-success" style="font-size:1.3rem;">UGX {{ number_format($revenue) }}</div></div><div class="stat-icon green"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="2" x2="12" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div></div>
</div>

@if($impounded->count())
<div class="card" style="padding:0;">
    <div class="card-header" style="padding:14px 20px;"><div class="card-title">Vehicles Impounded ({{ $impounded->count() }})</div></div>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>Case #</th><th>Plate</th><th>Make/Model</th><th>Owner</th><th>Violation</th><th>Officer</th><th>Time</th></tr></thead>
            <tbody>
            @foreach($impounded as $v)
            <tr>
                <td><a href="{{ route('vehicles.show',$v) }}" class="text-primary fw-600">{{ $v->case_number }}</a></td>
                <td class="fw-600">{{ $v->plate_number }}</td>
                <td>{{ $v->make }} {{ $v->model }}</td>
                <td>{{ $v->owner->full_name }}</td>
                <td>{{ $v->violations->map(fn($x)=>$x->violationType->name)->join(', ') }}</td>
                <td>{{ $v->officer->name }}</td>
                <td>{{ $v->impounded_at->format('H:i') }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($payments->count())
<div class="card" style="padding:0;margin-top:20px;">
    <div class="card-header" style="padding:14px 20px;"><div class="card-title">Payments Received ({{ $payments->count() }})</div></div>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>Receipt #</th><th>Vehicle</th><th>Owner</th><th>Amount</th><th>Method</th><th>Time</th></tr></thead>
            <tbody>
            @foreach($payments as $p)
            <tr>
                <td class="text-primary fw-600">{{ $p->receipt_number }}</td>
                <td>{{ $p->vehicle->plate_number }}</td>
                <td>{{ $p->vehicle->owner->full_name }}</td>
                <td class="fw-600 text-success">UGX {{ number_format($p->amount) }}</td>
                <td>{{ $p->payment_method }}</td>
                <td>{{ $p->paid_at->format('H:i') }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
