<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'fine_id','vehicle_id','received_by','receipt_number',
        'amount','payment_method','bank_reference','notes','paid_at',
    ];

    protected $casts = ['amount' => 'decimal:2', 'paid_at' => 'datetime'];

    public function fine()       { return $this->belongsTo(Fine::class); }
    public function vehicle()    { return $this->belongsTo(Vehicle::class); }
    public function receivedBy() { return $this->belongsTo(User::class, 'received_by'); }

    public static function generateReceiptNumber(): string
    {
        $count = self::count() + 1;
        return sprintf('REC-%s-%05d', date('Ymd'), $count);
    }
}
