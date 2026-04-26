<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Fine;
use App\Models\Payment;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_impounded'     => Vehicle::where('status', Vehicle::STATUS_IMPOUNDED)->count(),
            'pending_payment'     => Vehicle::where('status', Vehicle::STATUS_PENDING_PAYMENT)->count(),
            'cleared'             => Vehicle::where('status', Vehicle::STATUS_CLEARED)->count(),
            'released'            => Vehicle::where('status', Vehicle::STATUS_RELEASED)->count(),
            'auctioned'           => Vehicle::where('status', Vehicle::STATUS_AUCTIONED)->count(),
            'total_vehicles'      => Vehicle::count(),
            'overdue_vehicles'    => Vehicle::whereIn('status', [Vehicle::STATUS_IMPOUNDED, Vehicle::STATUS_PENDING_PAYMENT])
                                        ->where('impounded_at', '<=', now()->subDays(30))->count(),
            'revenue_today'       => Payment::whereDate('paid_at', today())->sum('amount'),
            'revenue_month'       => Payment::whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year)->sum('amount'),
            'revenue_total'       => Payment::sum('amount'),
            'outstanding_fines'   => Fine::where('status', '!=', 'Paid')->sum('balance'),
        ];

        // Monthly revenue chart data (last 6 months)
        $monthlyRevenue = Payment::select(
            DB::raw('MONTH(paid_at) as month'),
            DB::raw('YEAR(paid_at) as year'),
            DB::raw('SUM(amount) as total')
        )
        ->where('paid_at', '>=', now()->subMonths(6))
        ->groupBy('year', 'month')
        ->orderBy('year')->orderBy('month')
        ->get()
        ->map(function ($r) {
            $r->label = date('M Y', mktime(0, 0, 0, $r->month, 1, $r->year));
            return $r;
        });

        // Recent vehicles
        $recentVehicles = Vehicle::with(['owner','officer'])->latest()->take(10)->get();

        // Recent audit logs
        $recentLogs = AuditLog::with('user')->latest()->take(8)->get();

        return view('dashboard.index', compact('stats', 'monthlyRevenue', 'recentVehicles', 'recentLogs'));
    }
}
