<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\ReleaseForm;
use App\Models\VehicleStatusLog;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ReleaseFormController extends Controller
{
    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['owner', 'officer', 'fine', 'violations.violationType', 'releaseForm.authorizedBy']);
        return view('release-form.show', compact('vehicle'));
    }

    public function generate(Request $request, Vehicle $vehicle)
    {
        // Guard: must be cleared
        if (!in_array($vehicle->status, [Vehicle::STATUS_CLEARED, Vehicle::STATUS_RELEASED])) {
            return back()->with('error', 'Vehicle must be Cleared before generating a release form.');
        }

        $form = $vehicle->releaseForm ?? ReleaseForm::create([
            'vehicle_id'            => $vehicle->id,
            'form_number'           => ReleaseForm::generateFormNumber(),
            'authorized_by'         => auth()->id(),
            'issued_to'             => $vehicle->owner_id,
            'conditions_of_release' => $request->conditions ?? 'Vehicle released in as-is condition.',
            'issued_at'             => now(),
        ]);

        if ($vehicle->status === Vehicle::STATUS_CLEARED) {
            $old = $vehicle->status;
            $vehicle->update([
                'status'      => Vehicle::STATUS_RELEASED,
                'released_at' => now(),
                'released_by' => auth()->id(),
            ]);
            VehicleStatusLog::create([
                'vehicle_id' => $vehicle->id, 'old_status' => $old,
                'new_status' => Vehicle::STATUS_RELEASED,
                'changed_by' => auth()->id(),
                'reason'     => "Release form #{$form->form_number} issued",
                'changed_at' => now(),
            ]);
        }

        AuditLog::record('release_form.generated', $form);
        return redirect()->route('release-form.show', $vehicle)->with('success', "Release Form {$form->form_number} generated.");
    }
}
