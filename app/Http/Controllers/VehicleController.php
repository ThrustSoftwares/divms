<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleOwner;
use App\Models\ViolationType;
use App\Models\Fine;
use App\Models\VehicleStatusLog;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::with(['owner', 'officer', 'fine']);

        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('search'))   $query->where(function($q) use ($request) {
            $q->where('plate_number','like',"%{$request->search}%")
              ->orWhere('case_number','like',"%{$request->search}%")
              ->orWhereHas('owner', fn($o) => $o->where('full_name','like',"%{$request->search}%"));
        });
        if ($request->filled('date_from')) $query->whereDate('impounded_at', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->whereDate('impounded_at', '<=', $request->date_to);

        $vehicles = $query->orderByDesc('impounded_at')->paginate(15)->withQueryString();
        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        $owners         = VehicleOwner::orderBy('full_name')->get();
        $violationTypes = ViolationType::where('is_active', true)->get();
        return view('vehicles.create', compact('owners', 'violationTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'plate_number'     => 'required|string|max:20',
            'make'             => 'required|string|max:50',
            'model'            => 'required|string|max:50',
            'color'            => 'required|string|max:30',
            'year'             => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'chassis_number'   => 'nullable|string|max:50',
            'engine_number'    => 'nullable|string|max:50',
            'vehicle_type'     => 'required|string',
            'impound_location' => 'required|string|max:255',
            'impounded_at'     => 'required|date',
            'notes'            => 'nullable|string',
            // Owner
            'owner_type'       => 'required|in:existing,new',
            'owner_id'         => 'required_if:owner_type,existing|nullable|exists:vehicle_owners,id',
            'owner_name'       => 'required_if:owner_type,new|string|max:100',
            'owner_phone'      => 'required_if:owner_type,new|string|max:20',
            'owner_national_id'=> 'nullable|string|max:20',
            'owner_email'      => 'nullable|email|max:100',
            'owner_address'    => 'nullable|string',
            // Violations
            'violations'       => 'required|array|min:1',
            'violations.*'     => 'exists:violation_types,id',
            // Images
            'images.*'         => 'nullable|image|max:5120',
        ]);

        DB::transaction(function () use ($data, $request) {
            // Owner
            if ($data['owner_type'] === 'new') {
                $owner = VehicleOwner::create([
                    'full_name'   => $data['owner_name'],
                    'phone'       => $data['owner_phone'],
                    'national_id' => $data['owner_national_id'] ?? null,
                    'email'       => $data['owner_email'] ?? null,
                    'address'     => $data['owner_address'] ?? null,
                ]);
            } else {
                $owner = VehicleOwner::findOrFail($data['owner_id']);
            }

            // Vehicle
            $vehicle = Vehicle::create([
                'case_number'     => Vehicle::generateCaseNumber(),
                'plate_number'    => strtoupper($data['plate_number']),
                'make'            => $data['make'],
                'model'           => $data['model'],
                'color'           => $data['color'],
                'year'            => $data['year'] ?? null,
                'chassis_number'  => $data['chassis_number'] ?? null,
                'engine_number'   => $data['engine_number'] ?? null,
                'vehicle_type'    => $data['vehicle_type'],
                'status'          => Vehicle::STATUS_IMPOUNDED,
                'owner_id'        => $owner->id,
                'impounded_by'    => auth()->id(),
                'impound_location'=> $data['impound_location'],
                'impounded_at'    => $data['impounded_at'],
                'notes'           => $data['notes'] ?? null,
            ]);

            // Violations
            $baseFine = 0;
            $maxDailyFee = 0;
            foreach ($data['violations'] as $vtId) {
                $vt = ViolationType::find($vtId);
                $vehicle->violations()->create(['violation_type_id' => $vtId]);
                $baseFine    += $vt->base_fine;
                $maxDailyFee  = max($maxDailyFee, $vt->daily_storage_fee);
            }

            // Auto fine calculation
            $days = $vehicle->storageDays();
            $storageFee = $days * $maxDailyFee;
            $total = $baseFine + $storageFee;
            Fine::create([
                'vehicle_id'       => $vehicle->id,
                'base_fine_amount' => $baseFine,
                'storage_fee'      => $storageFee,
                'total_amount'     => $total,
                'balance'          => $total,
                'storage_days'     => $days,
                'status'           => 'Unpaid',
                'due_date'         => now()->addDays(14),
            ]);

            // Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $path = $img->store('vehicles', 'public');
                    $vehicle->images()->create([
                        'image_path'  => $path,
                        'uploaded_by' => auth()->id(),
                    ]);
                }
            }

            // Status log
            VehicleStatusLog::create([
                'vehicle_id' => $vehicle->id,
                'old_status' => null,
                'new_status' => Vehicle::STATUS_IMPOUNDED,
                'changed_by' => auth()->id(),
                'reason'     => 'Vehicle impounded',
                'changed_at' => now(),
            ]);

            AuditLog::record('vehicle.created', $vehicle, [], $vehicle->toArray());
        });

        return redirect()->route('vehicles.index')->with('success', 'Vehicle impounded and registered successfully.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['owner','officer','violations.violationType','fine.payments.receivedBy','images','statusLogs.changedBy','releaseForm']);
        // Recalculate fine to keep storage days fresh
        if ($vehicle->fine) $vehicle->fine->recalculate();
        return view('vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $owners         = VehicleOwner::orderBy('full_name')->get();
        $violationTypes = ViolationType::where('is_active', true)->get();
        $vehicle->load(['violations']);
        return view('vehicles.edit', compact('vehicle', 'owners', 'violationTypes'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'plate_number'     => 'required|string|max:20',
            'make'             => 'required|string|max:50',
            'model'            => 'required|string|max:50',
            'color'            => 'required|string|max:30',
            'year'             => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'chassis_number'   => 'nullable|string|max:50',
            'engine_number'    => 'nullable|string|max:50',
            'vehicle_type'     => 'required|string',
            'impound_location' => 'required|string|max:255',
            'notes'            => 'nullable|string',
        ]);

        $old = $vehicle->toArray();
        $vehicle->update($data);
        AuditLog::record('vehicle.updated', $vehicle, $old, $vehicle->fresh()->toArray());

        return redirect()->route('vehicles.show', $vehicle)->with('success', 'Vehicle details updated.');
    }

    public function updateStatus(Request $request, Vehicle $vehicle)
    {
        $allowed = [Vehicle::STATUS_IMPOUNDED, Vehicle::STATUS_PENDING_PAYMENT, Vehicle::STATUS_CLEARED, Vehicle::STATUS_RELEASED, Vehicle::STATUS_AUCTIONED];
        $request->validate(['status' => 'required|in:' . implode(',', $allowed), 'reason' => 'nullable|string']);

        // Guard: cannot release without cleared fine
        if ($request->status === Vehicle::STATUS_RELEASED) {
            $fine = $vehicle->fine;
            if ($fine && $fine->status !== 'Paid') {
                return back()->with('error', 'Cannot release vehicle. Outstanding fine balance of UGX ' . number_format($fine->balance) . ' must be paid first.');
            }
        }

        $old = $vehicle->status;
        $vehicle->update([
            'status'      => $request->status,
            'released_at' => in_array($request->status, [Vehicle::STATUS_RELEASED]) ? now() : $vehicle->released_at,
            'released_by' => $request->status === Vehicle::STATUS_RELEASED ? auth()->id() : $vehicle->released_by,
        ]);

        VehicleStatusLog::create([
            'vehicle_id' => $vehicle->id,
            'old_status' => $old,
            'new_status' => $request->status,
            'changed_by' => auth()->id(),
            'reason'     => $request->reason,
            'changed_at' => now(),
        ]);

        AuditLog::record('vehicle.status_changed', $vehicle, ['status' => $old], ['status' => $request->status]);
        return back()->with('success', "Vehicle status changed to {$request->status}.");
    }
}
