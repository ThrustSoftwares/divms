@extends('layouts.app')
@section('title', "Release Form")
@section('page-title', 'Vehicle Release Form')
@section('content')

<div class="page-header no-print">
    <div class="page-header-title">Release Form</div>
    <div class="flex gap-8">
        <button onclick="window.print()" class="btn btn-primary">🖨️ Print Form</button>
        <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-secondary">← Back</a>
    </div>
</div>

<div class="card" style="max-width:720px;margin:0 auto;">
    <!-- Header -->
    <div style="text-align:center;margin-bottom:24px;padding-bottom:20px;border-bottom:2px solid var(--border);">
        <div style="font-size:1.25rem;font-weight:800;color:var(--primary);">REPUBLIC OF UGANDA</div>
        <div style="font-size:1rem;font-weight:700;color:var(--text);">UGANDA POLICE FORCE</div>
        <div style="font-size:0.9rem;color:var(--text-muted);">Jinja Road Police Division — Kampala</div>
        <div style="margin-top:14px;background:var(--primary);color:white;padding:8px 24px;border-radius:6px;display:inline-block;font-size:1rem;font-weight:700;letter-spacing:1px;">
            VEHICLE RELEASE AUTHORIZATION FORM
        </div>
        @if($vehicle->releaseForm)
        <div style="margin-top:8px;font-size:0.82rem;color:var(--text-muted);">Form No: <strong>{{ $vehicle->releaseForm->form_number }}</strong>   ·   Date: <strong>{{ $vehicle->releaseForm->issued_at->format('M d, Y') }}</strong></div>
        @endif
    </div>

    <!-- Vehicle Details -->
    <div style="margin-bottom:20px;">
        <div style="font-weight:700;font-size:0.88rem;color:var(--primary);margin-bottom:10px;text-transform:uppercase;letter-spacing:0.5px;">Section A: Vehicle Particulars</div>
        <table style="width:100%;border-collapse:collapse;">
            <tr><td style="padding:6px 0;color:var(--text-muted);width:45%;">Case Number</td><td style="padding:6px 0;font-weight:600;">{{ $vehicle->case_number }}</td></tr>
            <tr><td style="padding:6px 0;color:var(--text-muted);">Plate Number</td><td style="padding:6px 0;font-weight:700;color:var(--primary);">{{ $vehicle->plate_number }}</td></tr>
            <tr><td style="padding:6px 0;color:var(--text-muted);">Make / Model</td><td style="padding:6px 0;font-weight:600;">{{ $vehicle->make }} {{ $vehicle->model }}</td></tr>
            <tr><td style="padding:6px 0;color:var(--text-muted);">Color / Year</td><td style="padding:6px 0;">{{ $vehicle->color }} / {{ $vehicle->year ?? 'N/A' }}</td></tr>
            <tr><td style="padding:6px 0;color:var(--text-muted);">Chassis Number</td><td style="padding:6px 0;">{{ $vehicle->chassis_number ?? 'N/A' }}</td></tr>
            <tr><td style="padding:6px 0;color:var(--text-muted);">Date Impounded</td><td style="padding:6px 0;">{{ $vehicle->impounded_at->format('M d, Y') }}</td></tr>
            <tr><td style="padding:6px 0;color:var(--text-muted);">Impound Location</td><td style="padding:6px 0;">{{ $vehicle->impound_location }}</td></tr>
            <tr><td style="padding:6px 0;color:var(--text-muted);">Violation(s)</td>
                <td style="padding:6px 0;">{{ $vehicle->violations->map(fn($v)=>$v->violationType->name)->join(', ') }}</td>
            </tr>
        </table>
    </div>

    <hr class="divider">

    <!-- Owner Details -->
    <div style="margin-bottom:20px;">
        <div style="font-weight:700;font-size:0.88rem;color:var(--primary);margin-bottom:10px;text-transform:uppercase;letter-spacing:0.5px;">Section B: Owner / Claimant</div>
        <table style="width:100%;border-collapse:collapse;">
            <tr><td style="padding:6px 0;color:var(--text-muted);width:45%;">Full Name</td><td style="padding:6px 0;font-weight:600;">{{ $vehicle->owner->full_name }}</td></tr>
            <tr><td style="padding:6px 0;color:var(--text-muted);">National ID</td><td style="padding:6px 0;">{{ $vehicle->owner->national_id ?? 'N/A' }}</td></tr>
            <tr><td style="padding:6px 0;color:var(--text-muted);">Phone</td><td style="padding:6px 0;">{{ $vehicle->owner->phone }}</td></tr>
            <tr><td style="padding:6px 0;color:var(--text-muted);">Address</td><td style="padding:6px 0;">{{ $vehicle->owner->address ?? 'N/A' }}</td></tr>
        </table>
    </div>

    <hr class="divider">

    <!-- Fine Summary -->
    @if($vehicle->fine)
    <div style="margin-bottom:20px;">
        <div style="font-weight:700;font-size:0.88rem;color:var(--primary);margin-bottom:10px;text-transform:uppercase;letter-spacing:0.5px;">Section C: Fine & Payment Status</div>
        <table style="width:100%;border-collapse:collapse;">
            <tr><td style="padding:6px 0;color:var(--text-muted);width:45%;">Total Fine Assessed</td><td style="padding:6px 0;font-weight:600;">UGX {{ number_format($vehicle->fine->total_amount) }}</td></tr>
            <tr><td style="padding:6px 0;color:var(--text-muted);">Amount Paid</td><td style="padding:6px 0;font-weight:600;color:var(--success);">UGX {{ number_format($vehicle->fine->amount_paid) }}</td></tr>
            <tr><td style="padding:6px 0;color:var(--text-muted);">Balance Outstanding</td><td style="padding:6px 0;font-weight:700;color:{{ $vehicle->fine->balance > 0 ? 'var(--danger)' : 'var(--success)' }};">UGX {{ number_format($vehicle->fine->balance) }}</td></tr>
            <tr><td style="padding:6px 0;color:var(--text-muted);">Payment Status</td><td style="padding:6px 0;font-weight:700;">{{ strtoupper($vehicle->fine->status) }}</td></tr>
        </table>
    </div>

    <hr class="divider">
    @endif

    <!-- Conditions -->
    <div style="margin-bottom:20px;">
        <div style="font-weight:700;font-size:0.88rem;color:var(--primary);margin-bottom:8px;text-transform:uppercase;letter-spacing:0.5px;">Section D: Conditions of Release</div>
        <div style="border:1px solid var(--border);border-radius:8px;padding:14px;font-size:0.85rem;line-height:1.6;">
            {{ $vehicle->releaseForm?->conditions_of_release ?? 'Vehicle released in as-is condition. The claimant confirms receipt of the vehicle and acknowledges settlement of all outstanding fines.' }}
        </div>
    </div>

    <!-- Signatures -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;margin-top:30px;padding-top:20px;border-top:1.5px solid var(--border);">
        <div>
            <div style="margin-bottom:35px;font-size:0.8rem;color:var(--text-muted);">Authorized by:</div>
            <div style="border-top:1.5px solid var(--text);padding-top:6px;">
                <div style="font-size:0.82rem;font-weight:600;">{{ $vehicle->releaseForm?->authorizedBy->name ?? '____________________' }}</div>
                <div style="font-size:0.76rem;color:var(--text-muted);">Rank: {{ $vehicle->releaseForm?->authorizedBy->rank ?? '____________________' }}</div>
                <div style="font-size:0.76rem;color:var(--text-muted);">Date: ____________________</div>
            </div>
        </div>
        <div>
            <div style="margin-bottom:35px;font-size:0.8rem;color:var(--text-muted);">Received by (Claimant):</div>
            <div style="border-top:1.5px solid var(--text);padding-top:6px;">
                <div style="font-size:0.82rem;font-weight:600;">{{ $vehicle->owner->full_name }}</div>
                <div style="font-size:0.76rem;color:var(--text-muted);">National ID: {{ $vehicle->owner->national_id ?? '____________________' }}</div>
                <div style="font-size:0.76rem;color:var(--text-muted);">Date: ____________________</div>
            </div>
        </div>
    </div>

    <div style="text-align:center;margin-top:20px;padding-top:16px;border-top:1px dashed var(--border);font-size:0.7rem;color:var(--text-muted);">
        This form was generated by DIVMS · Jinja Road Police Division · Uganda Police Force · {{ now()->format('Y') }}
    </div>
</div>
@endsection
