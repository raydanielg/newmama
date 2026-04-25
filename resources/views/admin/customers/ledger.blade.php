@extends('layouts.admin')

@section('title', 'Customer Ledger')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $customer->name }}</h3>
        <p>{{ $customer->customer_number }} · {{ $customer->segment ? ucfirst($customer->segment) : '—' }}</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.customers', ['type' => $customer->customer_type]) }}" class="btn-primary" style="text-decoration:none;">Back to Customers</a>
        <a href="{{ route('admin.customers.edit', $customer) }}" class="btn-icon" style="text-decoration:none;">Edit</a>
    </div>
</div>

@if($openInvoicesCount > 0)
<div class="content-card" style="border: 1px solid rgba(255,71,87,.25); background: rgba(255,71,87,.06); margin-bottom: 16px;">
    <div style="padding: 14px; display:flex; justify-content:space-between; align-items:center; gap:12px;">
        <div>
            <div style="font-weight:700; color: #b91c1c;">{{ $openInvoicesCount }} Open Invoice{{ $openInvoicesCount > 1 ? 's' : '' }}</div>
            <div style="margin-top:4px;">Total outstanding: <strong style="color:#b91c1c;">{{ number_format($totalOutstanding, 2) }}</strong></div>
        </div>
        <div style="opacity:.8;">Highlighted below</div>
    </div>
</div>
@endif

<div class="content-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Ref</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th class="td-right">Debit</th>
                    <th class="td-right">Credit</th>
                    <th class="td-right">Balance</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $e)
                    @php
                        $isOpen = $e->is_open && (float) $e->amount > 0;
                        $isOverdue = $e->due_date && $e->is_open && $e->due_date->isPast();
                    @endphp
                    <tr @if($isOverdue) style="background: rgba(255,71,87,.04);" @elseif($isOpen) style="background: rgba(212,135,74,.04);" @endif>
                        <td>{{ $e->posting_date?->format('M d, Y') ?? '-' }}</td>
                        <td>{{ $e->document_ref }}</td>
                        <td>{{ str_replace('_', ' ', $e->document_type) }}</td>
                        <td>{{ $e->description ?? '-' }}</td>
                        <td class="td-right">{{ (float) $e->amount > 0 ? number_format((float) $e->amount, 2) : '—' }}</td>
                        <td class="td-right">{{ (float) $e->amount < 0 ? number_format(abs((float) $e->amount), 2) : '—' }}</td>
                        <td class="td-right" style="font-weight:700;">{{ number_format(abs((float) $e->running_balance), 2) }} {{ (float) $e->running_balance > 0 ? 'DR' : 'CR' }}</td>
                        <td>{{ $e->due_date?->format('M d, Y') ?? '—' }}</td>
                        <td>
                            @if($e->is_open && (float) $e->amount > 0)
                                <span class="badge status-pregnant">{{ $isOverdue ? 'Overdue' : 'Open' }}</span>
                            @elseif((float) $e->amount < 0)
                                <span class="badge status-trying">Payment</span>
                            @else
                                <span class="badge">Closed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding: 18px;">No ledger entries yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
