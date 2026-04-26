<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleOwner extends Model
{
    protected $fillable = [
        'full_name','national_id','phone','email',
        'address','next_of_kin_name','next_of_kin_phone',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'owner_id');
    }

    public function releaseForms()
    {
        return $this->hasMany(ReleaseForm::class, 'issued_to');
    }
}
