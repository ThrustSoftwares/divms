@extends('layouts.app')
@section('title', "Record Payment")
@section('page-title', 'Record Payment')
@section('content')

<div class="page-header">
    <div>
        <div class="page-header-title">Record Payment</div>
        <div class="page-header-sub">{{ $vehicle->case_number }} · {{ $vehicle->plate_number }}</div>
    </div>
    <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-secondary">← Back to Vehicle</a>
</div>

<div class="grid-2">
<form method="POST" action="{{ route('payments.store', $vehicle) }}">
@csrf
<div class="card">
    <div class="card-header"><div class="card-title">Payment Details</div></div>
    <div class="form-grid">
        <div class="form-group">
            <label class="form-label">Amount (UGX) *</label>
            @if($vehicle->fine)
            <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('amount').value = {{ $vehicle->fine->balance }}">Pay Full Balance</button>
                <span class="text-muted" style="font-size:0.82rem;">Remaining: UGX {{ number_format($vehicle->fine->balance) }}</span>
            </div>
            @endif
            <input id="amount" type="number" name="amount" class="form-control @error('amount') is-invalid @enderror"
                value="{{ old('amount', $vehicle->fine?->balance) }}" min="1" required>
            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Payment Method *</label>
            <select name="payment_method" class="form-control" required>
                @foreach(['Cash','Bank','Mobile Money'] as $m)
                <option value="{{ $m }}" {{ old('payment_method') === $m ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Bank Reference / Transaction ID</label>
            <input type="text" name="bank_reference" class="form-control" value="{{ old('bank_reference') }}" placeholder="Optional">
        </div>
        <div class="form-group">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
        </div>
    </div>
    <div class="flex justify-between mt-16">
        <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-success" style="padding:11px 28px;">
            ✓ Confirm Payment
        </button>
    </div>
</div>
</form>

<!-- Fine Summary Card -->
@if($vehicle->fine)
<div>
    <div class="card" style="border-left:4px solid var(--primary);">
        <div class="card-header"><div class="card-title">Fine Summary</div></div>
        <div style="display:flex;flex-direction:column;gap:10px;">
            <div class="flex justify-between"><span class="text-muted">Base Fine</span><span class="fw-600">UGX {{ number_format($vehicle->fine->base_fine_amount) }}</span></div>
            <div class="flex justify-between"><span class="text-muted">Storage Fee ({{ $vehicle->fine->storage_days }} days)</span><span class="fw-600">UGX {{ number_format($vehicle->fine->storage_fee) }}</span></div>
            <hr class="divider" style="margin:4px 0;">
            <div class="flex justify-between"><span class="text-muted">Total Fine</span><span class="fw-700 text-danger">UGX {{ number_format($vehicle->fine->total_amount) }}</span></div>
            <div class="flex justify-between"><span class="text-muted">Already Paid</span><span class="fw-600 text-success">UGX {{ number_format($vehicle->fine->amount_paid) }}</span></div>
            <div class="flex justify-between" style="background:var(--primary-50);padding:10px;border-radius:8px;margin-top:4px;">
                <span class="fw-600">Outstanding Balance</span>
                <span class="fw-700 text-danger" style="font-size:1.1rem;">UGX {{ number_format($vehicle->fine->balance) }}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><div class="card-title">Vehicle</div></div>
        <div class="detail-grid">
            <div class="detail-item"><div class="detail-label">Plate</div><div class="detail-value fw-600">{{ $vehicle->plate_number }}</div></div>
            <div class="detail-item"><div class="detail-label">Owner</div><div class="detail-value">{{ $vehicle->owner->full_name }}</div></div>
            <div class="detail-item"><div class="detail-label">Days Held</div><div class="detail-value">{{ $vehicle->storageDays() }} days</div></div>
            <div class="detail-item"><div class="detail-label">Status</div><div class="detail-value">{{ $vehicle->status }}</div></div>
        </div>
    </div>
</div>
@endif
</div>
@endsection
