<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Vehicle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'case_number','plate_number','make','model','color','year',
        'chassis_number','engine_number','vehicle_type','status',
        'owner_id','impounded_by','impound_location','impounded_at',
        'released_at','released_by','notes',
    ];

    protected $casts = [
        'impounded_at' => 'datetime',
        'released_at'  => 'datetime',
    ];

    // Status constants
    const STATUS_IMPOUNDED       = 'Impounded';
    const STATUS_PENDING_PAYMENT = 'Pending Payment';
    const STATUS_CLEARED         = 'Cleared';
    const STATUS_RELEASED        = 'Released';
    const STATUS_AUCTIONED       = 'Auctioned';

    public static function generateCaseNumber(): string
    {
        $prefix = 'JRPD';
        $year   = date('Y');
        $count  = self::whereYear('created_at', $year)->count() + 1;
        return sprintf('%s-%s-%04d', $prefix, $year, $count);
    }

    public function owner()         { return $this->belongsTo(VehicleOwner::class, 'owner_id'); }
    public function officer()       { return $this->belongsTo(User::class, 'impounded_by'); }
    public function releasedBy()    { return $this->belongsTo(User::class, 'released_by'); }
    public function violations()    { return $this->hasMany(VehicleViolation::class); }
    public function fine()          { return $this->hasOne(Fine::class); }
    public function payments()      { return $this->hasMany(Payment::class); }
    public function images()        { return $this->hasMany(VehicleImage::class); }
    public function statusLogs()    { return $this->hasMany(VehicleStatusLog::class); }
    public function releaseForm()   { return $this->hasOne(ReleaseForm::class); }

    public function storageDays(): int
    {
        $end = $this->released_at ?? now();
        return (int) Carbon::parse($this->impounded_at)->diffInDays($end);
    }

    public function isOverdue(): bool
    {
        return in_array($this->status, [self::STATUS_IMPOUNDED, self::STATUS_PENDING_PAYMENT])
            && $this->storageDays() > 30;
    }
}
