@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Reports')
@section('content')

<div class="page-header">
    <div class="page-header-title">Reports & Analytics</div>
    <div class="text-muted">Generate daily, monthly, and revenue reports</div>
</div>

<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
    <a href="{{ route('reports.daily') }}" class="stat-card" style="text-decoration:none;cursor:pointer;">
        <div>
            <div class="stat-label">Daily Report</div>
            <div style="font-size:1rem;font-weight:600;color:var(--text);margin-top:6px;">Impounds & payments for any given day</div>
            <div class="btn btn-primary btn-sm mt-12">Open Report →</div>
        </div>
        <div class="stat-icon blue">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
    </a>
    <a href="{{ route('reports.monthly') }}" class="stat-card" style="text-decoration:none;cursor:pointer;">
        <div>
            <div class="stat-label">Monthly Report</div>
            <div style="font-size:1rem;font-weight:600;color:var(--text);margin-top:6px;">Monthly summary with daily breakdown</div>
            <div class="btn btn-primary btn-sm mt-12">Open Report →</div>
        </div>
        <div class="stat-icon orange">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/></svg>
        </div>
    </a>
    <a href="{{ route('reports.revenue') }}" class="stat-card" style="text-decoration:none;cursor:pointer;">
        <div>
            <div class="stat-label">Revenue Report</div>
            <div style="font-size:1rem;font-weight:600;color:var(--text);margin-top:6px;">Payments and outstanding balances</div>
            <div class="btn btn-success btn-sm mt-12">Open Report →</div>
        </div>
        <div class="stat-icon green">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="2" x2="12" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
    </a>
</div>
@endsection
