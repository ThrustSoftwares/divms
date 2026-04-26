<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'role_id','name','badge_number','email','phone',
        'rank','department','password','is_active',
    ];

    protected $hidden = ['password','remember_token'];

    protected $casts = ['email_verified_at' => 'datetime', 'is_active' => 'boolean'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->role && $this->role->name === $role;
    }

    public function isAdmin(): bool      { return $this->hasRole('admin'); }
    public function isOfficer(): bool    { return $this->hasRole('officer'); }
    public function isFinance(): bool    { return $this->hasRole('finance_officer'); }

    public function impoundedVehicles()  { return $this->hasMany(Vehicle::class, 'impounded_by'); }
    public function auditLogs()          { return $this->hasMany(AuditLog::class); }
    public function payments()           { return $this->hasMany(Payment::class, 'received_by'); }
}
