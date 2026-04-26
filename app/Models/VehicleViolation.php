<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleViolation extends Model
{
    protected $fillable = ['vehicle_id','violation_type_id','description'];

    public function vehicle()       { return $this->belongsTo(Vehicle::class); }
    public function violationType() { return $this->belongsTo(ViolationType::class); }
}
