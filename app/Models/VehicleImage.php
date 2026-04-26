<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleImage extends Model
{
    protected $fillable = ['vehicle_id','image_path','caption','uploaded_by'];

    public function vehicle()    { return $this->belongsTo(Vehicle::class); }
    public function uploadedBy() { return $this->belongsTo(User::class, 'uploaded_by'); }
}
