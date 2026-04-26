<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Payment;
use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function daily(Request $request)
    {
        $date = $request->date ?? today()->toDateString();

        $impounded = Vehicle::whereDate('impounded_at', $date)->with('owner','officer','violations.violationType')->get();
        $released  = Vehicle::whereDate('released_at', $date)->with('owner','officer')->get();
        $payments  = Payment::whereDate('paid_at', $date)->with('vehicle.owner','receivedBy')->get();
        $revenue   = $payments->sum('amount');

        return view('reports.daily', compact('date', 'impounded', 'released', 'payments', 'revenue'));
    }

    public function monthly(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        [$year, $mon] = explode('-', $month);

        $impounded = Vehicle::whereYear('impounded_at', $year)->whereMonth('impounded_at', $mon)->count();
        $released  = Vehicle::whereYear('released_at', $year)->whereMonth('released_at', $mon)->count();
        $revenue   = Payment::whereYear('paid_at', $year)->whereMonth('paid_at', $mon)->sum('amount');
        $auctioned = Vehicle::where('status', Vehicle::STATUS_AUCTIONED)
                            ->whereYear('updated_at', $year)->whereMonth('updated_at', $mon)->count();

        $dailyBreakdown = Payment::select(
            DB::raw('DATE(paid_at) as day'), DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count')
        )->whereYear('paid_at', $year)->whereMonth('paid_at', $mon)
         ->groupBy('day')->orderBy('day')->get();

        return view('reports.monthly', compact('month','impounded','released','revenue','auctioned','dailyBreakdown'));
    }

    public function revenue(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to   ?? today()->toDateString();

        $payments = Payment::with(['vehicle.owner','receivedBy'])
                           ->whereBetween('paid_at', [$from, $to . ' 23:59:59'])
                           ->orderByDesc('paid_at')->get();

        $byMethod = $payments->groupBy('payment_method')->map->sum('amount');
        $total    = $payments->sum('amount');
        $outstanding = Fine::where('status','!=','Paid')->sum('balance');

        return view('reports.revenue', compact('payments','byMethod','total','outstanding','from','to'));
    }
}
