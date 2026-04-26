<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->orderBy('name')->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:100',
            'badge_number' => 'nullable|string|unique:users,badge_number',
            'email'        => 'required|email|unique:users,email',
            'phone'        => 'nullable|string|max:20',
            'rank'         => 'nullable|string|max:50',
            'department'   => 'nullable|string|max:100',
            'role_id'      => 'required|exists:roles,id',
            'password'     => 'required|min:8|confirmed',
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        AuditLog::record('user.created', $user, [], $user->toArray());

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:100',
            'badge_number' => 'nullable|string|unique:users,badge_number,' . $user->id,
            'phone'        => 'nullable|string|max:20',
            'rank'         => 'nullable|string|max:50',
            'department'   => 'nullable|string|max:100',
            'role_id'      => 'required|exists:roles,id',
            'is_active'    => 'boolean',
            'password'     => 'nullable|min:8|confirmed',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $old = $user->toArray();
        $user->update($data);
        AuditLog::record('user.updated', $user, $old, $user->fresh()->toArray());

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) return back()->with('error', 'Cannot deactivate your own account.');
        $user->update(['is_active' => false]);
        AuditLog::record('user.deactivated', $user);
        return redirect()->route('users.index')->with('success', 'User deactivated.');
    }
}
