@extends('layouts.app')
@section('title', "Monthly Report")
@section('page-title', 'Monthly Report')
@section('content')

<div class="page-header">
    <div class="page-header-title">Monthly Report — {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}</div>
    <div class="flex gap-8">
        <button onclick="window.print()" class="btn btn-primary no-print">🖨️ Print</button>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary no-print">← Back</a>
    </div>
</div>

<form method="GET" class="filter-bar no-print">
    <div class="form-group">
        <label class="form-label">Select Month</label>
        <input type="month" name="month" class="form-control" value="{{ $month }}">
    </div>
    <button type="submit" class="btn btn-primary" style="align-self:flex-end;">Generate</button>
</form>

<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);">
    <div class="stat-card"><div><div class="stat-label">Impounded</div><div class="stat-value">{{ $impounded }}</div></div><div class="stat-icon blue"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v9"/></svg></div></div>
    <div class="stat-card"><div><div class="stat-label">Released</div><div class="stat-value">{{ $released }}</div></div><div class="stat-icon green"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div></div>
    <div class="stat-card"><div><div class="stat-label">Auctioned</div><div class="stat-value">{{ $auctioned }}</div></div><div class="stat-icon red"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/></svg></div></div>
    <div class="stat-card"><div><div class="stat-label">Revenue</div><div class="stat-value text-primary" style="font-size:1.2rem;">UGX {{ number_format($revenue) }}</div></div><div class="stat-icon teal"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="2" x2="12" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div></div>
</div>

@if($dailyBreakdown->count())
<div class="card" style="padding:0;">
    <div class="card-header" style="padding:14px 20px;"><div class="card-title">Daily Revenue Breakdown</div></div>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>Date</th><th>Transactions</th><th>Revenue (UGX)</th></tr></thead>
            <tbody>
            @foreach($dailyBreakdown as $d)
            <tr>
                <td>{{ \Carbon\Carbon::parse($d->day)->format('l, M d, Y') }}</td>
                <td>{{ $d->count }}</td>
                <td class="fw-600 text-success">{{ number_format($d->total) }}</td>
            </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr style="background:var(--primary-50);">
                    <td class="fw-700" style="padding:12px 16px;">Monthly Total</td>
                    <td class="fw-700">{{ $dailyBreakdown->sum('count') }}</td>
                    <td class="fw-700 text-primary" style="padding:12px 16px;">UGX {{ number_format($revenue) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endif
@endsection
