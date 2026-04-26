<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationType extends Model
{
    protected $fillable = ['code','name','description','base_fine','daily_storage_fee','is_active'];

    protected $casts = ['base_fine' => 'decimal:2', 'daily_storage_fee' => 'decimal:2', 'is_active' => 'boolean'];

    public function violations()
    {
        return $this->hasMany(VehicleViolation::class);
    }
}
