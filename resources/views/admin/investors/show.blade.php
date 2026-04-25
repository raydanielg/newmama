@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $investor->name }}</h3>
        <p>{{ $investor->investor_number }} · {{ $investor->phone ?: '—' }} · {{ $investor->email ?: '—' }}</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors') }}">Back</a>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.reports', ['investor_id' => $investor->id]) }}">Report</a>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.edit', $investor) }}">Edit</a>
        <form method="POST" action="{{ route('admin.investors.toggle-status', $investor) }}" style="display:inline;">
            @csrf
            <button class="btn-icon" type="submit" style="border:0; background:transparent; cursor:pointer;">
                {{ $investor->status === 'active' ? 'Deactivate' : 'Activate' }}
            </button>
        </form>
    </div>
</div>

<div class="stats-grid" style="margin-bottom:14px;">
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">TSh {{ number_format((float) $kpis['balance'], 2) }}</h3><p class="stat-label">Balance</p></div></div>
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">TSh {{ number_format((float) $kpis['ytd_inflows'], 2) }}</h3><p class="stat-label">YTD Inflows</p></div></div>
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">TSh {{ number_format((float) $kpis['ytd_outflows'], 2) }}</h3><p class="stat-label">YTD Outflows</p></div></div>
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">{{ ucfirst($investor->status) }}</h3><p class="stat-label">Status</p></div></div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <div style="font-weight:900; margin-bottom:10px;">Post Transaction</div>
    <form method="POST" action="{{ route('admin.investors.transactions.store', $investor) }}" style="display:grid; grid-template-columns: 160px 220px 180px 160px 160px 1fr 140px; gap:10px; align-items:end;">
        @csrf
        <div>
            <label class="form-label">Date</label>
            <input type="date" name="posting_date" value="{{ now()->toDateString() }}" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Type</label>
            <select name="type" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="contribution">Contribution</option>
                <option value="withdrawal">Withdrawal</option>
                <option value="dividend">Dividend</option>
                <option value="adjustment">Adjustment</option>
            </select>
        </div>
        <div>
            <label class="form-label">Amount</label>
            <input type="number" step="0.01" min="0.01" name="amount" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; text-align:right; font-family:var(--mono); font-weight:900;">
        </div>
        <div>
            <label class="form-label">Method</label>
            <input name="method" placeholder="cash/bank" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Reference</label>
            <input name="reference" placeholder="Optional" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Description</label>
            <input name="description" placeholder="Optional" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <button class="btn-primary" type="submit">Post</button>
        </div>
    </form>
</div>

<div style="display:grid; grid-template-columns: 0.85fr 1.15fr; gap:14px;">
    <div class="content-card" style="padding:16px;">
        <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>Investor Details</h3></div>
        <div style="display:grid; gap:10px;">
            <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;">
                <div style="color:#6b7280; font-size:12px;">ID Number</div>
                <div style="font-weight:800;">{{ $investor->id_number ?: '—' }}</div>
            </div>
            <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;">
                <div style="color:#6b7280; font-size:12px;">Address</div>
                <div style="font-weight:800;">{{ $investor->address ?: '—' }}</div>
            </div>
            @if($investor->notes)
                <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;">
                    <div style="color:#6b7280; font-size:12px;">Notes</div>
                    <div style="font-weight:700;">{{ $investor->notes }}</div>
                </div>
            @endif
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <span class="badge status-trying">KYC Ready</span>
                <span class="badge status-trying">Active Portfolio</span>
                @if($investor->status !== 'active')
                    <span class="badge status-pregnant">Review</span>
                @endif
            </div>
        </div>
    </div>

    <div class="content-card" style="padding:16px;">
        <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>Transactions</h3></div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:120px;">Date</th>
                        <th style="width:140px;">Type</th>
                        <th>Description</th>
                        <th style="width:180px; text-align:right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                        <tr>
                            <td style="font-family:var(--mono);">{{ optional($t->posting_date)->toDateString() }}</td>
                            <td>{{ strtoupper($t->type) }}</td>
                            <td>{{ $t->description ?: ($t->reference ?: '—') }}</td>
                            <td style="text-align:right; font-family:var(--mono); font-weight:800;">
                                {{ number_format((float) $t->amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center; color:#6b7280; padding:18px;">No transactions yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:14px;">{{ $transactions->links() }}</div>
    </div>
</div>
@endsection
