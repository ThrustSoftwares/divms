@extends('layouts.app')
@section('title', "Receipt #{$payment->receipt_number}")
@section('page-title', 'Payment Receipt')
@section('content')

<div class="page-header no-print">
    <div class="page-header-title">Payment Receipt</div>
    <div class="flex gap-8">
        <button onclick="window.print()" class="btn btn-primary">🖨️ Print Receipt</button>
        <a href="{{ route('vehicles.show', $payment->vehicle) }}" class="btn btn-secondary">← Back</a>
    </div>
</div>

<div class="card" style="max-width:680px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:24px;padding-bottom:20px;border-bottom:2px solid var(--border);">
        <div style="font-size:1.3rem;font-weight:800;color:var(--primary);">JINJA ROAD POLICE DIVISION</div>
        <div style="font-size:0.85rem;color:var(--text-muted);">Digital Impounded Vehicle Management System</div>
        <div style="font-size:0.8rem;color:var(--text-muted);">Kampala, Uganda · Uganda Police Force</div>
        <div style="margin-top:12px;font-size:1.1rem;font-weight:700;color:var(--text);background:var(--primary-50);padding:8px 20px;border-radius:8px;display:inline-block;">
            OFFICIAL PAYMENT RECEIPT
        </div>
    </div>

    <div class="detail-grid mb-24">
        <div class="detail-item"><div class="detail-label">Receipt Number</div><div class="detail-value fw-700 text-primary text-right" style="font-size:1.1rem;">{{ $payment->receipt_number }}</div></div>
        <div class="detail-item"><div class="detail-label">Payment Date</div><div class="detail-value">{{ $payment->paid_at->format('M d, Y H:i') }}</div></div>
        <div class="detail-item"><div class="detail-label">Case Number</div><div class="detail-value fw-600">{{ $payment->vehicle->case_number }}</div></div>
        <div class="detail-item"><div class="detail-label">Plate Number</div><div class="detail-value fw-600">{{ $payment->vehicle->plate_number }}</div></div>
        <div class="detail-item"><div class="detail-label">Vehicle</div><div class="detail-value">{{ $payment->vehicle->make }} {{ $payment->vehicle->model }} ({{ $payment->vehicle->color }})</div></div>
        <div class="detail-item"><div class="detail-label">Owner</div><div class="detail-value">{{ $payment->vehicle->owner->full_name }}</div></div>
        <div class="detail-item"><div class="detail-label">Owner Phone</div><div class="detail-value">{{ $payment->vehicle->owner->phone }}</div></div>
        <div class="detail-item"><div class="detail-label">Payment Method</div><div class="detail-value">{{ $payment->payment_method }}</div></div>
        @if($payment->bank_reference)
        <div class="detail-item" style="grid-column:1/-1;"><div class="detail-label">Bank Reference</div><div class="detail-value">{{ $payment->bank_reference }}</div></div>
        @endif
        <div class="detail-item"><div class="detail-label">Received By</div><div class="detail-value">{{ $payment->receivedBy->name }}</div></div>
    </div>

    <div style="background:var(--primary-50);border:2px solid var(--primary-200);border-radius:10px;padding:20px;text-align:center;margin-bottom:20px;">
        <div style="font-size:0.8rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:1px;">Amount Paid</div>
        <div style="font-size:2.2rem;font-weight:800;color:var(--primary);margin-top:4px;">UGX {{ number_format($payment->amount) }}</div>
    </div>

    @if($payment->fine)
    <div style="display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap;margin-bottom:20px;">
        <div class="text-center" style="flex:1;padding:12px;background:#F8FFF8;border-radius:8px;border:1px solid #A5D6A7;">
            <div class="text-muted" style="font-size:0.76rem;">Total Fine</div>
            <div class="fw-700" style="color:var(--text);">UGX {{ number_format($payment->fine->total_amount) }}</div>
        </div>
        <div class="text-center" style="flex:1;padding:12px;background:#E8F5E9;border-radius:8px;border:1px solid #A5D6A7;">
            <div class="text-muted" style="font-size:0.76rem;">Total Paid</div>
            <div class="fw-700 text-success">UGX {{ number_format($payment->fine->amount_paid) }}</div>
        </div>
        <div class="text-center" style="flex:1;padding:12px;background:{{ $payment->fine->balance > 0 ? '#FFF3E0' : '#E8F5E9' }};border-radius:8px;">
            <div class="text-muted" style="font-size:0.76rem;">Balance</div>
            <div class="fw-700 {{ $payment->fine->balance > 0 ? 'text-warning' : 'text-success' }}">
                UGX {{ number_format($payment->fine->balance) }}
            </div>
        </div>
    </div>
    @endif

    <div style="margin-top:24px;padding-top:20px;border-top:1px dashed var(--border);display:flex;justify-content:space-between;align-items:flex-end;">
        <div>
            <div class="text-muted" style="font-size:0.76rem;margin-bottom:30px;">Authorized Signature</div>
            <div style="border-top:1.5px solid var(--text);padding-top:4px;width:180px;font-size:0.76rem;color:var(--text-muted);">Finance Officer / Cashier</div>
        </div>
        <div style="text-align:center;">
            <div style="font-size:0.65rem;color:var(--text-muted);">This is an official receipt of<br>Jinja Road Police Division, Uganda</div>
        </div>
    </div>
</div>
@endsection
