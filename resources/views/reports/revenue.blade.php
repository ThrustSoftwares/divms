@extends('layouts.app')
@section('title', 'Revenue Report')
@section('page-title', 'Revenue Report')
@section('content')

<div class="page-header">
    <div class="page-header-title">Revenue Report</div>
    <button onclick="window.print()" class="btn btn-primary no-print">🖨️ Print</button>
</div>

<form method="GET" class="filter-bar no-print">
    <div class="form-group">
        <label class="form-label">From Date</label>
        <input type="date" name="from" class="form-control" value="{{ $from }}">
    </div>
    <div class="form-group">
        <label class="form-label">To Date</label>
        <input type="date" name="to" class="form-control" value="{{ $to }}">
    </div>
    <div class="form-group" style="justify-content:flex-end;">
        <button type="submit" class="btn btn-primary">Generate</button>
    </div>
</form>

<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
    <div class="stat-card">
        <div><div class="stat-label">Total Revenue</div><div class="stat-value text-primary">UGX {{ number_format($total) }}</div><div class="stat-sub">{{ $from }} to {{ $to }}</div></div>
        <div class="stat-icon blue"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="2" x2="12" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
    </div>
    <div class="stat-card">
        <div><div class="stat-label">Payments Count</div><div class="stat-value">{{ number_format($payments->count()) }}</div><div class="stat-sub">Transactions</div></div>
        <div class="stat-icon green"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div>
    </div>
    <div class="stat-card">
        <div><div class="stat-label">Outstanding Fines</div><div class="stat-value text-danger">UGX {{ number_format($outstanding) }}</div><div class="stat-sub">Uncollected balance</div></div>
        <div class="stat-icon red"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/></svg></div>
    </div>
</div>

@if($byMethod->count())
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><div class="card-title">Revenue by Payment Method</div></div>
    <div class="flex gap-16" style="flex-wrap:wrap;">
        @foreach($byMethod as $method => $amount)
        <div style="flex:1;min-width:180px;padding:16px;background:var(--primary-50);border-radius:10px;border:1.5px solid var(--primary-100);">
            <div class="text-muted" style="font-size:0.76rem;font-weight:600;text-transform:uppercase;">{{ $method }}</div>
            <div style="font-size:1.3rem;font-weight:700;color:var(--primary);margin-top:4px;">UGX {{ number_format($amount) }}</div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="card" style="padding:0;">
    <div class="card-header" style="padding:16px 20px;"><div class="card-title">Payment Transactions</div></div>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>Receipt #</th><th>Date</th><th>Vehicle</th><th>Owner</th><th>Method</th><th>Amount</th><th>Received By</th></tr></thead>
            <tbody>
            @forelse($payments as $p)
            <tr>
                <td><a href="{{ route('payments.receipt', $p) }}" class="text-primary fw-600">{{ $p->receipt_number }}</a></td>
                <td>{{ $p->paid_at->format('M d, Y H:i') }}</td>
                <td>
                    <a href="{{ route('vehicles.show', $p->vehicle) }}" class="text-primary">{{ $p->vehicle->case_number }}</a><br>
                    <span class="text-muted" style="font-size:0.76rem;">{{ $p->vehicle->plate_number }}</span>
                </td>
                <td>{{ $p->vehicle->owner->full_name }}</td>
                <td>{{ $p->payment_method }}</td>
                <td class="fw-700 text-success">UGX {{ number_format($p->amount) }}</td>
                <td>{{ $p->receivedBy->name }}</td>
            </tr>
            @empty
            <tr><td colspan="7" class="empty-state">No payments found for this period</td></tr>
            @endforelse
            </tbody>
            <tfoot>
                <tr style="background:var(--primary-50);">
                    <td colspan="5" class="fw-700" style="padding:12px 16px;">Total</td>
                    <td class="fw-700 text-primary" style="padding:12px 16px;">UGX {{ number_format($total) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
