<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only log non-GET requests or specific actions if needed
        if ($request->isMethod('GET')) {
            return $response;
        }

        $user = Auth::user();
        
        // Determine action based on route or request
        $action = $request->route() ? $request->route()->getName() : $request->path();
        
        AuditLog::create([
            'user_id' => $user ? $user->id : null,
            'action' => $action ?: 'system.action',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'new_values' => json_encode($request->except(['password', 'password_confirmation', '_token'])),
        ]);

        return $response;
    }
}
