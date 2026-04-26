<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\Vehicle;
use App\Models\Payment;
use App\Models\VehicleStatusLog;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function create(Vehicle $vehicle)
    {
        $vehicle->load(['fine', 'owner']);
        if ($vehicle->fine) $vehicle->fine->recalculate();
        return view('payments.create', compact('vehicle'));
    }

    public function store(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'amount'          => 'required|numeric|min:1|max:' . ($vehicle->fine->balance ?? 999999999),
            'payment_method'  => 'required|in:Cash,Bank,Mobile Money',
            'bank_reference'  => 'nullable|string|max:100',
            'notes'           => 'nullable|string',
        ]);

        DB::transaction(function () use ($data, $vehicle) {
            $fine = $vehicle->fine;

            $payment = Payment::create([
                'fine_id'          => $fine->id,
                'vehicle_id'       => $vehicle->id,
                'received_by'      => auth()->id(),
                'receipt_number'   => Payment::generateReceiptNumber(),
                'amount'           => $data['amount'],
                'payment_method'   => $data['payment_method'],
                'bank_reference'   => $data['bank_reference'] ?? null,
                'notes'            => $data['notes'] ?? null,
                'paid_at'          => now(),
            ]);

            // Update fine
            $fine->increment('amount_paid', $data['amount']);
            $fine->decrement('balance', $data['amount']);
            $fine->refresh();
            $fine->update(['status' => $fine->balance <= 0 ? 'Paid' : 'Partial']);

            // Update vehicle status
            if ($fine->status === 'Paid' && $vehicle->status !== Vehicle::STATUS_CLEARED) {
                $old = $vehicle->status;
                $vehicle->update(['status' => Vehicle::STATUS_CLEARED]);
                VehicleStatusLog::create([
                    'vehicle_id' => $vehicle->id,
                    'old_status' => $old,
                    'new_status' => Vehicle::STATUS_CLEARED,
                    'changed_by' => auth()->id(),
                    'reason'     => "Payment cleared — Receipt #{$payment->receipt_number}",
                    'changed_at' => now(),
                ]);
            } elseif ($vehicle->status === Vehicle::STATUS_IMPOUNDED) {
                $old = $vehicle->status;
                $vehicle->update(['status' => Vehicle::STATUS_PENDING_PAYMENT]);
                VehicleStatusLog::create([
                    'vehicle_id' => $vehicle->id, 'old_status' => $old,
                    'new_status' => Vehicle::STATUS_PENDING_PAYMENT,
                    'changed_by' => auth()->id(),
                    'reason'     => 'Partial payment made',
                    'changed_at' => now(),
                ]);
            }

            AuditLog::record('payment.recorded', $payment, [], $payment->toArray());
        });

        return redirect()->route('vehicles.show', $vehicle)->with('success', 'Payment recorded successfully.');
    }

    public function receipt(Payment $payment)
    {
        $payment->load(['vehicle.owner', 'fine', 'receivedBy']);
        return view('payments.receipt', compact('payment'));
    }
}
