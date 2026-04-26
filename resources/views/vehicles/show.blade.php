@extends('layouts.app')
@section('title', "Vehicle #{$vehicle->case_number}")
@section('page-title', 'Vehicle Details')
@section('content')

@php
    $statusCls = ['Impounded'=>'badge-impounded','Pending Payment'=>'badge-pending','Cleared'=>'badge-cleared','Released'=>'badge-released','Auctioned'=>'badge-auctioned'][$vehicle->status] ?? '';
@endphp

<div class="page-header">
    <div>
        <div class="page-header-title">{{ $vehicle->case_number }}</div>
        <div class="page-header-sub">{{ $vehicle->plate_number }} · {{ $vehicle->make }} {{ $vehicle->model }} · {{ $vehicle->color }}</div>
    </div>
    <div class="flex gap-8">
        @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
        <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-secondary">Edit</a>
        @endif
        <a href="{{ route('vehicles.index') }}" class="btn btn-white">← Back</a>
    </div>
</div>

<div class="grid-2">
<!-- Left Column -->
<div>
    <!-- Vehicle Info -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Vehicle Information</div>
            <span class="badge {{ $statusCls }}">{{ $vehicle->status }}</span>
        </div>
        <div class="detail-grid">
            <div class="detail-item"><div class="detail-label">Case Number</div><div class="detail-value fw-600 text-primary">{{ $vehicle->case_number }}</div></div>
            <div class="detail-item"><div class="detail-label">Plate Number</div><div class="detail-value fw-600">{{ $vehicle->plate_number }}</div></div>
            <div class="detail-item"><div class="detail-label">Make</div><div class="detail-value">{{ $vehicle->make }}</div></div>
            <div class="detail-item"><div class="detail-label">Model</div><div class="detail-value">{{ $vehicle->model }}</div></div>
            <div class="detail-item"><div class="detail-label">Color</div><div class="detail-value">{{ $vehicle->color }}</div></div>
            <div class="detail-item"><div class="detail-label">Year</div><div class="detail-value">{{ $vehicle->year ?? '—' }}</div></div>
            <div class="detail-item"><div class="detail-label">Type</div><div class="detail-value">{{ $vehicle->vehicle_type }}</div></div>
            <div class="detail-item"><div class="detail-label">Chassis No.</div><div class="detail-value">{{ $vehicle->chassis_number ?? '—' }}</div></div>
            <div class="detail-item" style="grid-column:1/-1;"><div class="detail-label">Impound Location</div><div class="detail-value">{{ $vehicle->impound_location }}</div></div>
            <div class="detail-item"><div class="detail-label">Impounded At</div><div class="detail-value">{{ $vehicle->impounded_at->format('M d, Y H:i') }}</div></div>
            <div class="detail-item"><div class="detail-label">Storage Days</div><div class="detail-value {{ $vehicle->storageDays() > 30 ? 'text-danger fw-600' : '' }}">{{ $vehicle->storageDays() }} days</div></div>
            <div class="detail-item"><div class="detail-label">Impounded By</div><div class="detail-value">{{ $vehicle->officer->name }}</div></div>
            @if($vehicle->released_at)
            <div class="detail-item"><div class="detail-label">Released At</div><div class="detail-value text-success">{{ $vehicle->released_at->format('M d, Y H:i') }}</div></div>
            @endif
            @if($vehicle->notes)
            <div class="detail-item" style="grid-column:1/-1;"><div class="detail-label">Notes</div><div class="detail-value">{{ $vehicle->notes }}</div></div>
            @endif
        </div>
    </div>

    <!-- Owner Info -->
    <div class="card">
        <div class="card-header"><div class="card-title">Owner Details</div></div>
        <div class="detail-grid">
            <div class="detail-item"><div class="detail-label">Full Name</div><div class="detail-value fw-600">{{ $vehicle->owner->full_name }}</div></div>
            <div class="detail-item"><div class="detail-label">Phone</div><div class="detail-value">{{ $vehicle->owner->phone }}</div></div>
            <div class="detail-item"><div class="detail-label">National ID</div><div class="detail-value">{{ $vehicle->owner->national_id ?? '—' }}</div></div>
            <div class="detail-item"><div class="detail-label">Email</div><div class="detail-value">{{ $vehicle->owner->email ?? '—' }}</div></div>
            <div class="detail-item" style="grid-column:1/-1;"><div class="detail-label">Address</div><div class="detail-value">{{ $vehicle->owner->address ?? '—' }}</div></div>
        </div>
    </div>

    <!-- Violations -->
    <div class="card">
        <div class="card-header"><div class="card-title">Violations Recorded</div></div>
        @forelse($vehicle->violations as $v)
        <div style="padding:10px 0;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <div>
                <div class="fw-600" style="font-size:0.875rem;">{{ $v->violationType->name }}</div>
                <div class="text-muted" style="font-size:0.76rem;">Code: {{ $v->violationType->code }}</div>
            </div>
            <div class="text-right">
                <div class="fw-600 text-danger">UGX {{ number_format($v->violationType->base_fine) }}</div>
                <div class="text-muted" style="font-size:0.76rem;">+ {{ number_format($v->violationType->daily_storage_fee) }}/day</div>
            </div>
        </div>
        @empty
        <p class="text-muted">No violations recorded</p>
        @endforelse
    </div>
</div>

<!-- Right Column -->
<div>
    <!-- Fine Summary -->
    @if($vehicle->fine)
    <div class="card" style="border-top:4px solid var(--primary);">
        <div class="card-header">
            <div class="card-title">Fine Summary</div>
            @php $fc = ['Unpaid'=>'badge-unpaid','Partial'=>'badge-partial','Paid'=>'badge-paid'][$vehicle->fine->status] ?? ''; @endphp
            <span class="badge {{ $fc }}">{{ $vehicle->fine->status }}</span>
        </div>
        <div class="detail-grid">
            <div class="detail-item"><div class="detail-label">Base Fine</div><div class="detail-value">UGX {{ number_format($vehicle->fine->base_fine_amount) }}</div></div>
            <div class="detail-item"><div class="detail-label">Storage Fee ({{ $vehicle->fine->storage_days }}d)</div><div class="detail-value">UGX {{ number_format($vehicle->fine->storage_fee) }}</div></div>
            <div class="detail-item"><div class="detail-label">Total Fine</div><div class="detail-value fw-700 text-danger" style="font-size:1.1rem;">UGX {{ number_format($vehicle->fine->total_amount) }}</div></div>
            <div class="detail-item"><div class="detail-label">Amount Paid</div><div class="detail-value text-success fw-600">UGX {{ number_format($vehicle->fine->amount_paid) }}</div></div>
            <div class="detail-item" style="grid-column:1/-1;background:var(--primary-50);padding:10px;border-radius:8px;">
                <div class="detail-label">Outstanding Balance</div>
                <div class="detail-value fw-700 {{ $vehicle->fine->balance > 0 ? 'text-danger' : 'text-success' }}" style="font-size:1.2rem;">UGX {{ number_format($vehicle->fine->balance) }}</div>
            </div>
        </div>
        @if((auth()->user()->isAdmin() || auth()->user()->isFinance()) && $vehicle->fine->status !== 'Paid')
        <a href="{{ route('payments.create', $vehicle) }}" class="btn btn-success w-full mt-16" style="justify-content:center;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 100 7h5a3.5 3.5 0 010 7H6"/></svg>
            Record Payment
        </a>
        @endif
    </div>

    <!-- Payment History -->
    @if($vehicle->fine->payments->count() > 0)
    <div class="card">
        <div class="card-header"><div class="card-title">Payment History</div></div>
        @foreach($vehicle->fine->payments as $p)
        <div style="padding:10px 0;border-bottom:1px solid var(--border);">
            <div class="flex justify-between items-center">
                <div>
                    <div class="fw-600" style="font-size:0.85rem;">{{ $p->receipt_number }}</div>
                    <div class="text-muted" style="font-size:0.75rem;">{{ $p->payment_method }} · {{ $p->paid_at->format('M d, Y H:i') }}</div>
                    <div class="text-muted" style="font-size:0.75rem;">by {{ $p->receivedBy->name }}</div>
                </div>
                <div class="fw-700 text-success" style="font-size:0.95rem;">UGX {{ number_format($p->amount) }}</div>
            </div>
            <a href="{{ route('payments.receipt', $p) }}" class="btn btn-secondary btn-sm mt-8">View Receipt</a>
        </div>
        @endforeach
    </div>
    @endif
    @endif

    <!-- Status Update -->
    @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
    <div class="card">
        <div class="card-header"><div class="card-title">Update Status</div></div>
        <form method="POST" action="{{ route('vehicles.update-status', $vehicle) }}">
            @csrf @method('PATCH')
            <div class="form-group mb-12">
                <label class="form-label">New Status</label>
                <select name="status" class="form-control" required>
                    @foreach(['Impounded','Pending Payment','Cleared','Released','Auctioned'] as $s)
                    <option value="{{ $s }}" {{ $vehicle->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-12">
                <label class="form-label">Reason for Change</label>
                <textarea name="reason" class="form-control" rows="2" placeholder="Reason..."></textarea>
            </div>
            <button type="submit" class="btn btn-warning w-full" style="justify-content:center;">Update Status</button>
        </form>

        @if(in_array($vehicle->status, ['Cleared','Released']))
        <hr class="divider">
        <form method="POST" action="{{ route('release-form.generate', $vehicle) }}">
            @csrf
            <button type="submit" class="btn btn-primary w-full" style="justify-content:center;">
                📄 Generate Release Form
            </button>
        </form>
        @endif
    </div>
    @endif

    <!-- Images -->
    @if($vehicle->images->count())
    <div class="card">
        <div class="card-header"><div class="card-title">Vehicle Images ({{ $vehicle->images->count() }})</div></div>
        <div class="img-gallery">
            @foreach($vehicle->images as $img)
            <img src="{{ Storage::url($img->image_path) }}" alt="Vehicle image" class="img-thumb">
            @endforeach
        </div>
    </div>
    @endif

    <!-- Status Log -->
    <div class="card">
        <div class="card-header"><div class="card-title">Status History</div></div>
        <div class="timeline">
            @forelse($vehicle->statusLogs as $log)
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-time">{{ $log->changed_at->format('M d, Y H:i') }} — {{ $log->changedBy->name }}</div>
                <div class="timeline-content">
                    @if($log->old_status)<strike>{{ $log->old_status }}</strike> →@endif
                    {{ $log->new_status }}
                    @if($log->reason) <span class="text-muted">({{ $log->reason }})</span>@endif
                </div>
            </div>
            @empty
            <p class="text-muted">No status history</p>
            @endforelse
        </div>
    </div>
</div>
</div>
@endsection
