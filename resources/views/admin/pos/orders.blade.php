@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>POS Orders</h3>
        <p>Manage receipts and recent transactions.</p>
    </div>
    <div class="header-actions" style="display:flex; gap:10px;">
        <a href="{{ route('admin.pos') }}" class="btn-primary" style="text-decoration:none;">New Sale</a>
    </div>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Payment</th>
                    <th>Total</th>
                    <th>Created</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                <tr>
                    <td style="font-family:var(--mono); font-weight:900;">{{ $o->order_number }}</td>
                    <td>{{ $o->mother?->full_name ?? $o->customer?->name ?? 'Walk-in' }}</td>
                    <td>{{ strtoupper($o->payment_method ?: '—') }}</td>
                    <td>TSh {{ number_format($o->grand_total, 0) }}</td>
                    <td>{{ $o->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        <a class="btn-icon" href="{{ route('admin.pos.receipt', $o) }}" style="text-decoration:none;">Receipt</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; padding:20px; color:#6b7280;">No orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:16px;">
        {{ $orders->links() }}
    </div>
</div>
@endsection
