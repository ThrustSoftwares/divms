<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleStatusLog extends Model
{
    protected $fillable = ['vehicle_id','old_status','new_status','changed_by','reason','changed_at'];

    protected $casts = ['changed_at' => 'datetime'];

    public function vehicle()   { return $this->belongsTo(Vehicle::class); }
    public function changedBy() { return $this->belongsTo(User::class, 'changed_by'); }
}
