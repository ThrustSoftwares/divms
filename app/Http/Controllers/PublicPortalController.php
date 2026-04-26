<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class PublicPortalController extends Controller
{
    public function index()
    {
        return view('public.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:20'
        ]);

        $plate = strtoupper(trim($request->plate_number));
        
        // Find the vehicle but explicitly exclude private ownership data
        // Only return vehicles currently in custody
        $vehicle = Vehicle::with(['fine', 'violations.violationType', 'images'])
            ->where('plate_number', $plate)
            ->whereIn('status', [
                Vehicle::STATUS_IMPOUNDED, 
                Vehicle::STATUS_PENDING_PAYMENT, 
                Vehicle::STATUS_CLEARED
            ])
            ->first();

        // Recalculate fine for accurate display if they are late
        if ($vehicle && $vehicle->fine) {
            $vehicle->fine->recalculate();
        }

        return view('public.index', compact('vehicle', 'plate'));
    }
}
