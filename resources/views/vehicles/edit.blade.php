@extends('layouts.app')
@section('title', "Edit Vehicle")
@section('page-title', 'Edit Vehicle Record')
@section('content')

<div class="page-header">
    <div>
        <div class="page-header-title">Edit: {{ $vehicle->case_number }}</div>
        <div class="page-header-sub">{{ $vehicle->plate_number }}</div>
    </div>
    <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-secondary">← Back</a>
</div>

<form method="POST" action="{{ route('vehicles.update', $vehicle) }}">
@csrf @method('PUT')

<div class="card">
    <div class="card-header"><div class="card-title">🚗 Vehicle Information</div></div>
    <div class="form-grid form-grid-3">
        <div class="form-group">
            <label class="form-label">Plate Number *</label>
            <input type="text" name="plate_number" class="form-control" value="{{ old('plate_number', $vehicle->plate_number) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Vehicle Type *</label>
            <select name="vehicle_type" class="form-control" required>
                @foreach(['Car','Truck','Motorcycle','Minibus','Bus','Trailer','Pickup'] as $t)
                <option value="{{ $t }}" {{ old('vehicle_type', $vehicle->vehicle_type) === $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Year</label>
            <input type="number" name="year" class="form-control" value="{{ old('year', $vehicle->year) }}" min="1900" max="{{ date('Y')+1 }}">
        </div>
        <div class="form-group">
            <label class="form-label">Make *</label>
            <input type="text" name="make" class="form-control" value="{{ old('make', $vehicle->make) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Model *</label>
            <input type="text" name="model" class="form-control" value="{{ old('model', $vehicle->model) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Color *</label>
            <input type="text" name="color" class="form-control" value="{{ old('color', $vehicle->color) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Chassis Number</label>
            <input type="text" name="chassis_number" class="form-control" value="{{ old('chassis_number', $vehicle->chassis_number) }}">
        </div>
        <div class="form-group">
            <label class="form-label">Engine Number</label>
            <input type="text" name="engine_number" class="form-control" value="{{ old('engine_number', $vehicle->engine_number) }}">
        </div>
        <div class="form-group" style="grid-column:1/-1;">
            <label class="form-label">Impound Location *</label>
            <input type="text" name="impound_location" class="form-control" value="{{ old('impound_location', $vehicle->impound_location) }}" required>
        </div>
        <div class="form-group" style="grid-column:1/-1;">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control">{{ old('notes', $vehicle->notes) }}</textarea>
        </div>
    </div>
</div>

<div class="flex justify-between mt-16">
    <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Save Changes</button>
</div>
</form>
@endsection
