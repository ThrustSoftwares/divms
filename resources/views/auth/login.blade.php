<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — DIVMS | Jinja Road Police Division</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <div class="auth-logo-icon">
                <svg width="30" height="30" viewBox="0 0 32 32" fill="none">
                    <path d="M6 22l4-8 4 4 4-6 4 10" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <div class="auth-title">DIVMS</div>
                <div class="auth-sub">Digital Impounded Vehicle Management System</div>
            </div>
        </div>

        <div style="margin-bottom:28px;">
            <h2 style="font-size:1.25rem;font-weight:700;color:#0d1b2e;">Welcome Back</h2>
            <p style="font-size:0.82rem;color:#5a7080;margin-top:4px;">Sign in to access the management system</p>
        </div>

        @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:16px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group" style="margin-bottom:16px;">
                <label class="form-label" for="email">Email Address</label>
                <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="officer@divms.ug" required autofocus>
            </div>
            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label" for="password">Password</label>
                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="••••••••" required>
            </div>
            <div class="form-check" style="margin-bottom:24px;">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember" style="font-size:0.85rem;cursor:pointer;">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary w-full" style="justify-content:center;padding:12px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                Sign In to DIVMS
            </button>
            <div style="text-align: center; margin-top: 16px;">
                <a href="{{ route('public.index') }}" style="color: #1565C0; text-decoration: none; font-size: 0.9rem; font-weight: 500;">&larr; Return to Public Search Portal</a>
            </div>
        </form>

        <div class="ugpf-badge">
            <strong>Jinja Road Police Division</strong><br>
            Uganda Police Force · Kampala · Secure System
        </div>
    </div>
</div>
</body>
</html>
