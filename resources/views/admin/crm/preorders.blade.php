@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Track customer pre-orders and fulfillment status.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.crm.hub') }}">Back</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.crm.preorders') }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px;">
        <input name="q" value="{{ request('q') }}" placeholder="Search customer/product/phone" style="flex:1; min-width:220px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <select name="status" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            <option value="">All</option>
            @foreach(['open' => 'Open', 'fulfilled' => 'Fulfilled', 'cancelled' => 'Cancelled'] as $k => $v)
                <option value="{{ $k }}" {{ request('status')===$k?'selected':'' }}>{{ $v }}</option>
            @endforeach
        </select>
        <button class="btn-primary" type="submit">Filter</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.crm.preorders') }}">Reset</a>
    </form>

    <form method="POST" action="{{ route('admin.crm.preorders.store') }}" style="display:grid; grid-template-columns: 240px 160px 1fr 120px 180px 120px; gap:10px; align-items:end;">
        @csrf
        <div>
            <label class="form-label">Customer</label>
            <select name="customer_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">— Optional —</option>
                @foreach($customers as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Phone</label>
            <input name="phone" placeholder="Optional" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Product Name</label>
            <input name="product_name" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Qty</label>
            <input type="number" step="0.01" min="0.01" name="qty" value="1" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; text-align:right; font-family:var(--mono);">
        </div>
        <div>
            <label class="form-label">Expected Date</label>
            <input type="date" name="expected_date" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <button class="btn-primary" type="submit">Add</button>
        </div>
        <div style="grid-column: 1 / -1;">
            <label class="form-label">Notes</label>
            <textarea name="notes" rows="2" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;"></textarea>
        </div>
    </form>
</div>

<div class="content-card" style="padding:16px;">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:110px;">Status</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th style="width:100px; text-align:right;">Qty</th>
                    <th style="width:140px;">Expected</th>
                    <th style="width:160px;">Phone</th>
                    <th style="width:160px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($preorders as $p)
                    <tr>
                        <td>{{ $p->status }}</td>
                        <td>{{ optional($p->customer)->name ?: ($p->customer_name ?: '—') }}</td>
                        <td>{{ $p->product_name }}</td>
                        <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $p->qty, 2) }}</td>
                        <td>{{ optional($p->expected_date)->toDateString() }}</td>
                        <td>{{ $p->phone }}</td>
                        <td style="text-align:right;">
                            @if($p->status === 'open')
                                <form method="POST" action="{{ route('admin.crm.preorders.close', $p) }}" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="status" value="fulfilled">
                                    <button class="btn-icon" type="submit">Fulfill</button>
                                </form>
                                <form method="POST" action="{{ route('admin.crm.preorders.close', $p) }}" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="status" value="cancelled">
                                    <button class="btn-icon" type="submit">Cancel</button>
                                </form>
                            @else
                                <span style="color:#6b7280;">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center; color:#6b7280; padding:18px;">No pre-orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $preorders->links() }}</div>
</div>
@endsection
