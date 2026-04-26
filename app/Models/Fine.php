<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    protected $fillable = [
        'vehicle_id','base_fine_amount','storage_fee','total_amount',
        'amount_paid','balance','storage_days','status','due_date',
    ];

    protected $casts = [
        'base_fine_amount' => 'decimal:2',
        'storage_fee'      => 'decimal:2',
        'total_amount'     => 'decimal:2',
        'amount_paid'      => 'decimal:2',
        'balance'          => 'decimal:2',
        'due_date'         => 'datetime',
    ];

    public function vehicle()  { return $this->belongsTo(Vehicle::class); }
    public function payments() { return $this->hasMany(Payment::class); }

    public function recalculate(): void
    {
        $vehicle = $this->vehicle;
        $baseFine = 0;
        $maxDailyFee = 0;

        foreach ($vehicle->violations as $v) {
            $vt = $v->violationType;
            $baseFine    += $vt->base_fine;
            $maxDailyFee  = max($maxDailyFee, $vt->daily_storage_fee);
        }

        $days             = $vehicle->storageDays();
        $storageFee       = $days * $maxDailyFee;
        $total            = $baseFine + $storageFee;
        $balance          = $total - $this->amount_paid;

        $this->update([
            'base_fine_amount' => $baseFine,
            'storage_fee'      => $storageFee,
            'total_amount'     => $total,
            'storage_days'     => $days,
            'balance'          => $balance,
            'status'           => $balance <= 0 ? 'Paid' : ($this->amount_paid > 0 ? 'Partial' : 'Unpaid'),
        ]);
    }
}
