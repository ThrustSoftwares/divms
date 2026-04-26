<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReleaseForm extends Model
{
    protected $fillable = [
        'vehicle_id','form_number','authorized_by',
        'issued_to','conditions_of_release','issued_at',
    ];

    protected $casts = ['issued_at' => 'datetime'];

    public function vehicle()    { return $this->belongsTo(Vehicle::class); }
    public function authorizedBy() { return $this->belongsTo(User::class, 'authorized_by'); }
    public function issuedTo()   { return $this->belongsTo(VehicleOwner::class, 'issued_to'); }

    public static function generateFormNumber(): string
    {
        $count = self::count() + 1;
        return sprintf('RF-%s-%04d', date('Y'), $count);
    }
}
