@extends('layouts.app')
@section('title', 'User Management')
@section('page-title', 'User Management')
@section('content')

<div class="page-header">
    <div class="page-header-title">System Users</div>
    <a href="{{ route('users.create') }}" class="btn btn-primary">+ Add User</a>
</div>

<div class="card" style="padding:0;">
    <div class="table-wrapper">
        <table>
            <thead><tr><th>Name</th><th>Badge #</th><th>Email</th><th>Rank</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($users as $u)
            <tr>
                <td class="fw-600">{{ $u->name }}</td>
                <td>{{ $u->badge_number ?? '—' }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->rank ?? '—' }}</td>
                <td><span class="badge badge-admin">{{ $u->role->display_name }}</span></td>
                <td>
                    @if($u->is_active)
                    <span class="badge badge-cleared">Active</span>
                    @else
                    <span class="badge badge-auctioned">Inactive</span>
                    @endif
                </td>
                <td>
                    <div class="flex gap-8">
                        <a href="{{ route('users.edit', $u) }}" class="btn btn-secondary btn-sm">Edit</a>
                        @if($u->id !== auth()->id())
                        <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('Deactivate this user?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">Deactivate</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="empty-state">No users found</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="pagination-wrap">{{ $users->links() }}</div>
@endsection
