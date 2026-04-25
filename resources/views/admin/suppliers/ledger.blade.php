@extends('layouts.admin')

@section('title', 'Supplier Ledger')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $supplier->name }}</h3>
        <p>{{ $supplier->code }} · {{ $supplier->contact_person ?: 'Vendor' }} · {{ $supplier->payment_terms ?: 'NET30' }}</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.suppliers') }}" class="btn-primary" style="text-decoration:none;">Back to Suppliers</a>
        <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn-icon" style="text-decoration:none;">Edit</a>
        <a href="{{ route('admin.suppliers.statement.csv', $supplier) }}?from={{ $from }}&to={{ $to }}" class="btn-icon" style="text-decoration:none;">CSV</a>
    </div>
</div>

<div class="content-card" style="margin-bottom:16px;">
    <div style="padding: 14px; display:flex; gap:12px; flex-wrap:wrap; align-items:end; justify-content:space-between;">
        <form method="GET" action="{{ route('admin.suppliers.ledger', $supplier) }}" style="display:flex; gap:10px; flex-wrap:wrap; align-items:end;">
            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">From</label>
                <input type="date" name="from" value="{{ $from }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>
            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">To</label>
                <input type="date" name="to" value="{{ $to }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>
            <button class="btn-primary" type="submit" style="height:42px;">Filter</button>
        </form>

        <div style="display:flex; gap:16px; flex-wrap:wrap; align-items:center;">
            <div style="font-size:12px; color:#6b7280;">AP Balance: <strong style="{{ (float) $supplier->balance_tzs > 0 ? 'color:#b91c1c;' : '' }}">{{ number_format(abs((float) $supplier->balance_tzs), 2) }} {{ (float) $supplier->balance_tzs > 0 ? 'DR' : 'CR' }}</strong></div>
            <div style="font-size:12px; color:#6b7280;">Entries: <strong>{{ number_format($entries->count()) }}</strong></div>
            <div style="font-size:12px; color:#6b7280;">Open Entries: <strong>{{ number_format($openEntriesCount) }}</strong></div>
        </div>
    </div>
</div>

@if($importOrderRefs->count() > 0)
<div class="content-card" style="margin-bottom:16px; background: rgba(133,194,190,.06); border: 1px solid rgba(133,194,190,.15);">
    <div style="padding: 14px;">
        <div style="font-weight:700; margin-bottom:8px;">Consignments Linked</div>
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
            @foreach($importOrderRefs as $ref)
                <span class="badge status-trying">{{ $ref }}</span>
            @endforeach
        </div>
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
                    <th>Consignment</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $e)
                    @php
                        $isOpen = $e->is_open && (float) $e->_amount > 0;
                    @endphp
                    <tr @if($isOpen) style="background: rgba(212,135,74,.04);" @endif>
                        <td>{{ $e->posting_date?->format('M d, Y') ?? '-' }}</td>
                        <td>{{ $e->document_ref }}</td>
                        <td>{{ str_replace('_', ' ', $e->document_type) }}</td>
                        <td>{{ $e->description ?? '-' }}</td>
                        <td class="td-right" style="color:#b91c1c;">{{ (float) $e->_amount > 0 ? number_format((float) $e->_amount, 2) : '—' }}</td>
                        <td class="td-right" style="color:#15803d;">{{ (float) $e->_amount < 0 ? number_format(abs((float) $e->_amount), 2) : '—' }}</td>
                        <td class="td-right" style="font-weight:700;">{{ number_format(abs((float) $e->running_balance), 2) }} {{ (float) $e->running_balance > 0 ? 'DR' : 'CR' }}</td>
                        <td>{{ $e->import_order_ref ?? '—' }}</td>
                        <td>
                            @if($isOpen)
                                <span class="badge status-pregnant">Open</span>
                            @elseif((float) $e->_amount < 0)
                                <span class="badge status-trying">Payment</span>
                            @else
                                <span class="badge">Closed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding: 18px;">No ledger entries for this period.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
