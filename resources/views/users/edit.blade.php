@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('content')
<div class="page-header">
    <div class="page-header-title">Edit: {{ $user->name }}</div>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">← Back</a>
</div>
<div class="card" style="max-width:700px;">
    <form method="POST" action="{{ route('users.update', $user) }}">
    @csrf @method('PUT')
    <div class="form-grid form-grid-2">
        <div class="form-group">
            <label class="form-label">Full Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Badge Number</label>
            <input type="text" name="badge_number" class="form-control" value="{{ old('badge_number', $user->badge_number) }}">
        </div>
        <div class="form-group">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
        </div>
        <div class="form-group">
            <label class="form-label">Rank</label>
            <input type="text" name="rank" class="form-control" value="{{ old('rank', $user->rank) }}">
        </div>
        <div class="form-group">
            <label class="form-label">Department</label>
            <input type="text" name="department" class="form-control" value="{{ old('department', $user->department) }}">
        </div>
        <div class="form-group">
            <label class="form-label">Role *</label>
            <select name="role_id" class="form-control" required>
                @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Active</label>
            <select name="is_active" class="form-control">
                <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div></div>
        <div class="form-group">
            <label class="form-label">New Password (leave blank to keep current)</label>
            <input type="password" name="password" class="form-control" placeholder="Min 8 characters">
        </div>
        <div class="form-group">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
    </div>
    <div class="flex justify-between mt-16">
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>
    </form>
</div>
@endsection
