<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Vehicle Search - DIVMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; display: flex; flex-direction: column; min-height: 100vh; margin:0;}
        .hero { background: linear-gradient(135deg, #1565C0, #0D47A1); color: white; padding: 80px 20px; text-align: center; position: relative;}
        .hero h1 { font-size: 2.5rem; font-weight: 700; margin-bottom: 10px; }
        .hero p { font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto 30px auto; }
        .search-box { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); max-width: 500px; margin: 0 auto; display: flex; gap:10px; }
        .search-box input { flex:1; padding: 15px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1.1rem; outline: none; transition: border 0.3s; text-transform: uppercase;}
        .search-box input:focus { border-color: #1565C0; }
        .search-box button { background: #1565C0; color: white; border: none; padding: 0 25px; border-radius: 8px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: background 0.3s; }
        .search-box button:hover { background: #0D47A1; }
        .content { flex: 1; max-width: 800px; margin: 40px auto; padding: 0 20px; width: 100%; box-sizing: border-box; }
        .card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .card-header { background: #f1f5f9; padding: 20px; border-bottom: 1px solid #e2e8f0; font-size: 1.2rem; font-weight: 600; color: #1e293b;}
        .detail-row { display: flex; padding: 15px 20px; border-bottom: 1px solid #f1f5f9; align-items:center;}
        .detail-row:last-child { border-bottom: none; }
        .detail-label { flex: 1; color: #64748b; font-weight: 500; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;}
        .detail-value { flex: 2; color: #0f172a; font-weight: 600; font-size: 1.05rem;}
        .badge { display: inline-block; background: #e2e8f0; color: #475569; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
        .badge-impounded { background: #fee2e2; color: #b91c1c; }
        .badge-pending { background: #fef3c7; color: #b45309; }
        .badge-cleared { background: #dcfce7; color: #15803d; }
        .text-danger { color: #dc2626; }
        .alert { background: #fee2e2; color: #b91c1c; padding: 20px; border-radius: 8px; text-align: center; font-weight: 500; font-size: 1.1rem; border: 1px solid #fca5a5;}
        .footer { background: #1e293b; color: white; text-align: center; padding: 20px; font-size: 0.9rem; margin-top:auto;}
        .login-link { position: absolute; top: 20px; right: 20px; color: white; text-decoration: none; font-weight: 500; background: rgba(255,255,255,0.2); padding: 8px 16px; border-radius: 20px; transition: all 0.3s; }
        .login-link:hover { background: rgba(255,255,255,0.3); }
        .instruction-box { background: #e0f2fe; padding: 15px 20px; border-radius: 8px; margin-top: 20px; color: #0369a1; font-weight: 500; font-size: 0.95rem; line-height: 1.5; border-left: 4px solid #0284c7;}
    </style>
</head>
<body>
    <div class="hero">
        <h1>Vehicle Inquiry Portal</h1>
        <p>Jinja Road Police Division — Enter your vehicle registration plate below to securely check its impound status and outstanding fines.</p>
        <form class="search-box" action="{{ route('public.search') }}" method="GET">
            <input type="text" name="plate_number" placeholder="Enter Registration No (e.g. UBA 123A)" value="{{ $plate ?? '' }}" required>
            <button type="submit">Check Status</button>
        </form>
    </div>

    <div class="content">
        @isset($plate)
            @if($vehicle)
                <div class="card">
                    <div class="card-header">
                        Vehicle Found: {{ $vehicle->plate_number }}
                    </div>
                    
                    @php
                        $statusCls = ['Impounded'=>'badge-impounded','Pending Payment'=>'badge-pending','Cleared'=>'badge-cleared'][$vehicle->status] ?? '';
                    @endphp

                    <div class="detail-row">
                        <div class="detail-label">Current Status</div>
                        <div class="detail-value"><span class="badge {{ $statusCls }}">{{ $vehicle->status }}</span></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Vehicle Details</div>
                        <div class="detail-value">{{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->color }})</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Impound Location</div>
                        <div class="detail-value">{{ $vehicle->impound_location }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Date Impounded</div>
                        <div class="detail-value">{{ $vehicle->impounded_at->format('M d, Y') }}</div>
                    </div>
                    
                    @if($vehicle->fine && $vehicle->fine->balance > 0)
                        <div class="detail-row" style="background: #fff1f2;">
                            <div class="detail-label">Outstanding Fine</div>
                            <div class="detail-value text-danger" style="font-size: 1.25rem;">
                                UGX {{ number_format($vehicle->fine->balance) }}
                            </div>
                        </div>
                    @elseif($vehicle->fine && $vehicle->fine->balance == 0)
                        <div class="detail-row" style="background: #f0fdf4;">
                            <div class="detail-label">Outstanding Fine</div>
                            <div class="detail-value" style="color: #16a34a;">Cleared (UGX 0)</div>
                        </div>
                    @endif

                    @if($vehicle->images && $vehicle->images->count() > 0)
                        <div style="padding: 20px; border-top: 1px solid #f1f5f9; background: #f8fafc; text-align: center;">
                            <div style="margin-bottom: 10px; font-weight: 600; color: #64748b; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Impound Photo</div>
                            @foreach($vehicle->images as $img)
                                <img src="{{ Storage::url($img->image_path) }}" alt="Impounded Vehicle" style="max-width: 100%; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 10px; border: 2px solid #e2e8f0;">
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="instruction-box">
                    <strong>Next Steps:</strong> Please proceed to Jinja Road Police Division with your original logbook and valid identification to process the release of this vehicle. All fines must be settled at the station via Bank or Mobile Money.
                </div>
            @else
                <div class="alert">
                    No impounded vehicle found with registration number <strong>{{ $plate }}</strong>.<br>
                    <span style="font-size: 0.9rem; font-weight: normal; opacity: 0.9;">Ensure you typed it correctly. If the vehicle was already cleared and released, it will not appear here.</span>
                </div>
            @endif
        @endisset
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Jinja Road Police Division. DIVMS Public Portal.
    </div>
</body>
</html>
