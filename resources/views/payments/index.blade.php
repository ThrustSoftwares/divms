@extends('layouts.app')
@section('title', 'Payments')
@section('content')

<div class="page-header">
    <div class="page-header-title">Payments</div>
    <div class="text-muted">View and search all recorded payments</div>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" action="{{ route('payments.index') }}" class="search-form">
            <input type="text" name="search" placeholder="Search by plate number or receipt..." value="{{ request('search') }}" class="search-input">
            <button type="submit" class="btn btn-primary btn-sm">Search</button>
            @if(request('search'))
                <a href="{{ route('payments.index') }}" class="btn btn-secondary btn-sm">Clear</a>
            @endif
        </form>
    </div>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Receipt #</th>
                    <th>Vehicle</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Received By</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td><strong>{{ $payment->receipt_number }}</strong></td>
                    <td>{{ $payment->vehicle->plate_number }}</td>
                    <td>{{ number_format($payment->amount, 2) }}</td>
                    <td>{{ $payment->payment_method }}</td>
                    <td>{{ $payment->receivedBy->name ?? 'N/A' }}</td>
                    <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        <a href="{{ route('payments.receipt', $payment) }}" class="btn btn-primary btn-sm">View Receipt</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No payments found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
    <div class="card-footer">
        {{ $payments->links() }}
    </div>
    @endif
</div>

<style>
.search-form {
    display: flex;
    gap: 8px;
    align-items: center;
}
.search-input {
    padding: 8px 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 14px;
    width: 250px;
}
</style>
@endsection