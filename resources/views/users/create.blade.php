@extends('layouts.app')
@section('title', 'Create User')
@section('page-title', 'Add New User')
@section('content')

<div class="page-header">
    <div class="page-header-title">Add System User</div>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">← Back</a>
</div>

<div class="card" style="max-width:700px;">
    <form method="POST" action="{{ route('users.store') }}">
    @csrf
    <div class="form-grid form-grid-2">
        <div class="form-group">
            <label class="form-label">Full Name *</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Badge Number</label>
            <input type="text" name="badge_number" class="form-control" value="{{ old('badge_number') }}" placeholder="OFF-101">
        </div>
        <div class="form-group">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+256700000000">
        </div>
        <div class="form-group">
            <label class="form-label">Rank</label>
            <input type="text" name="rank" class="form-control" value="{{ old('rank') }}" placeholder="Inspector">
        </div>
        <div class="form-group">
            <label class="form-label">Department</label>
            <input type="text" name="department" class="form-control" value="{{ old('department') }}" placeholder="Traffic">
        </div>
        <div class="form-group">
            <label class="form-label">Role *</label>
            <select name="role_id" class="form-control" required>
                <option value="">— Select Role —</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Password *</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min 8 characters" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group" style="grid-column:1/-1;">
            <label class="form-label">Confirm Password *</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
    </div>
    <div class="flex justify-between mt-16">
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Create User</button>
    </div>
    </form>
</div>
@endsection
