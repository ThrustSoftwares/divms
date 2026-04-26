@extends('layouts.app')
@section('title', 'Register Vehicle')
@section('page-title', 'Register Impounded Vehicle')
@section('content')

<div class="page-header">
    <div>
        <div class="page-header-title">New Vehicle Record</div>
        <div class="page-header-sub">Register a newly impounded vehicle</div>
    </div>
    <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">← Back</a>
</div>

<form method="POST" action="{{ route('vehicles.store') }}" enctype="multipart/form-data">
@csrf

@if ($errors->any())
    <div style="background:#FFEbee; color:#c62828; padding:16px; border-radius:8px; margin-bottom:20px; border: 1px solid #ffcdd2;">
        <strong>Please fix the following errors before registering:</strong>
        <ul style="margin-top:8px; margin-bottom:0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Vehicle Information -->
<div class="card">
    <div class="card-header"><div class="card-title">🚗 Vehicle Information</div></div>
    <div class="form-grid form-grid-3">
        <div class="form-group">
            <label class="form-label">Plate Number *</label>
            <input type="text" name="plate_number" class="form-control @error('plate_number') is-invalid @enderror"
                value="{{ old('plate_number') }}" placeholder="UAA 123A" required>
            @error('plate_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Vehicle Type *</label>
            <select name="vehicle_type" class="form-control" required>
                @foreach(['Car','Truck','Motorcycle','Minibus','Bus','Trailer','Pickup'] as $t)
                <option value="{{ $t }}" {{ old('vehicle_type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Year</label>
            <input type="number" name="year" class="form-control" value="{{ old('year') }}" placeholder="2020" min="1900" max="{{ date('Y')+1 }}">
        </div>
        <div class="form-group">
            <label class="form-label">Make *</label>
            <input type="text" name="make" class="form-control @error('make') is-invalid @enderror" value="{{ old('make') }}" placeholder="Toyota" required>
            @error('make')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Model *</label>
            <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model') }}" placeholder="Land Cruiser" required>
            @error('model')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Color *</label>
            <input type="text" name="color" class="form-control @error('color') is-invalid @enderror" value="{{ old('color') }}" placeholder="White" required>
            @error('color')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Chassis Number</label>
            <input type="text" name="chassis_number" class="form-control" value="{{ old('chassis_number') }}" placeholder="JN1AA5AP0CM826777">
        </div>
        <div class="form-group">
            <label class="form-label">Engine Number</label>
            <input type="text" name="engine_number" class="form-control" value="{{ old('engine_number') }}">
        </div>
        <div class="form-group">
            <label class="form-label">Impound Date & Time *</label>
            <input type="datetime-local" name="impounded_at" class="form-control" value="{{ old('impounded_at', now()->format('Y-m-d\TH:i')) }}" required>
        </div>
        <div class="form-group" style="grid-column:1/-1;">
            <label class="form-label">Impound Location *</label>
            <input type="text" name="impound_location" class="form-control" value="{{ old('impound_location') }}" placeholder="Jinja Road, near Total Petrol Station, Kampala" required>
        </div>
        <div class="form-group" style="grid-column:1/-1;">
            <label class="form-label">Notes / Remarks</label>
            <textarea name="notes" class="form-control" placeholder="Any additional remarks...">{{ old('notes') }}</textarea>
        </div>
    </div>
</div>

<!-- Violations -->
<div class="card">
    <div class="card-header"><div class="card-title">⚠️ Violation(s) *</div></div>
    @error('violations')<div class="alert alert-error mb-16">{{ $message }}</div>@enderror
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:12px;">
        @foreach($violationTypes as $vt)
        <label class="form-check" style="padding:12px 16px;border:1.5px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;transition:all 0.2s;">
            <input type="checkbox" name="violations[]" value="{{ $vt->id }}"
                {{ is_array(old('violations')) && in_array($vt->id, old('violations')) ? 'checked' : '' }}>
            <div>
                <div class="fw-600" style="font-size:0.875rem;">{{ $vt->name }}</div>
                <div class="text-muted" style="font-size:0.76rem;">Base fine: UGX {{ number_format($vt->base_fine) }} · Storage: UGX {{ number_format($vt->daily_storage_fee) }}/day</div>
            </div>
        </label>
        @endforeach
    </div>
</div>

<!-- Owner Information -->
<div class="card">
    <div class="card-header"><div class="card-title">👤 Owner Details</div></div>
    
    @php
        // If there are no existing owners yet, default to 'new' automatically
        $defaultOwnerType = $owners->isEmpty() ? 'new' : 'existing';
        $selectedOwnerType = old('owner_type', $defaultOwnerType);
    @endphp

    <div class="form-group mb-16">
        <label class="form-label">Owner Type *</label>
        <div class="flex gap-16 mt-8">
            <label class="form-check" style="{{ $owners->isEmpty() ? 'opacity: 0.5; pointer-events: none;' : '' }}">
                <input type="radio" name="owner_type" value="existing" {{ $selectedOwnerType === 'existing' ? 'checked' : '' }} onchange="toggleOwner(this.value)">
                Existing owner
            </label>
            <label class="form-check">
                <input type="radio" name="owner_type" value="new" {{ $selectedOwnerType === 'new' ? 'checked' : '' }} onchange="toggleOwner(this.value)">
                New owner (register)
            </label>
        </div>
    </div>

    <div id="existing-owner" style="display: {{ $selectedOwnerType === 'existing' ? 'block' : 'none' }};">
        <div class="form-group">
            <label class="form-label">Select Existing Owner</label>
            <select name="owner_id" class="form-control">
                <option value="">— Select owner —</option>
                @foreach($owners as $o)
                <option value="{{ $o->id }}" {{ old('owner_id') == $o->id ? 'selected' : '' }}>{{ $o->full_name }} — {{ $o->phone }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="new-owner" style="display: {{ $selectedOwnerType === 'new' ? 'block' : 'none' }};">
        <div class="form-grid form-grid-3">
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="owner_name" class="form-control" value="{{ old('owner_name') }}" placeholder="John Mugisha">
            </div>
            <div class="form-group">
                <label class="form-label">Phone *</label>
                <input type="text" name="owner_phone" class="form-control" value="{{ old('owner_phone') }}" placeholder="+256700000000">
            </div>
            <div class="form-group">
                <label class="form-label">National ID</label>
                <input type="text" name="owner_national_id" class="form-control" value="{{ old('owner_national_id') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="owner_email" class="form-control" value="{{ old('owner_email') }}">
            </div>
            <div class="form-group" style="grid-column:span 2;">
                <label class="form-label">Address</label>
                <input type="text" name="owner_address" class="form-control" value="{{ old('owner_address') }}" placeholder="Kampala, Uganda">
            </div>
        </div>
    </div>
</div>

<!-- Images -->
<div class="card">
    <div class="card-header"><div class="card-title">📷 Vehicle Images</div></div>
    <div class="form-group">
        <label class="form-label">Upload Images (max 5MB each)</label>
        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
        <div class="form-hint">Upload clear photos of the vehicle from all angles.</div>
    </div>
</div>

<div class="flex justify-between mt-16">
    <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary" style="padding:12px 32px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        Register & Calculate Fine
    </button>
</div>

</form>

@push('scripts')
<script>
function toggleOwner(val) {
    document.getElementById('existing-owner').style.display = val === 'existing' ? '' : 'none';
    document.getElementById('new-owner').style.display = val === 'new' ? '' : 'none';
}
// Highlight violation checkboxes
document.querySelectorAll('.form-check input[type=checkbox]').forEach(cb => {
    cb.addEventListener('change', () => {
        cb.closest('.form-check').style.borderColor = cb.checked ? '#1565C0' : 'var(--border)';
        cb.closest('.form-check').style.background = cb.checked ? '#E3F2FD' : '';
    });
    if (cb.checked) {
        cb.closest('.form-check').style.borderColor = '#1565C0';
        cb.closest('.form-check').style.background = '#E3F2FD';
    }
});
</script>
@endpush
@endsection
