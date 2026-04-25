@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Manage investors, balances, and contributions/withdrawals.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.hub') }}">Hub</a>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.portfolio') }}">Portfolio</a>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.reports') }}">Reports</a>
    </div>
</div>

<div class="stats-grid" style="margin-bottom:14px;">
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">{{ (int) $kpis['active_investors'] }}</h3><p class="stat-label">Active Investors</p></div></div>
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">TSh {{ number_format((float) $kpis['total_balance'], 2) }}</h3><p class="stat-label">Total Balance</p></div></div>
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">TSh {{ number_format((float) $kpis['mtd_inflows'], 2) }}</h3><p class="stat-label">MTD Contributions</p></div></div>
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">TSh {{ number_format((float) $kpis['mtd_outflows'], 2) }}</h3><p class="stat-label">MTD Withdrawals</p></div></div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.investors') }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px;">
        <input name="q" value="{{ request('q') }}" placeholder="Search name/number/phone/email" style="flex:1; min-width:260px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <select name="status" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            <option value="">All Status</option>
            <option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option>
            <option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>Inactive</option>
        </select>
        <button class="btn-primary" type="submit">Filter</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors') }}">Reset</a>
    </form>

    <div style="font-weight:800; margin-bottom:10px;">Add Investor</div>
    <form method="POST" action="{{ route('admin.investors.store') }}" style="display:grid; grid-template-columns: 1fr 180px 220px 160px 160px; gap:10px; align-items:end;">
        @csrf
        <div>
            <label class="form-label">Name</label>
            <input name="name" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Phone</label>
            <input name="phone" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Email</label>
            <input type="email" name="email" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">ID Number</label>
            <input name="id_number" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div style="grid-column: 1 / -1;">
            <label class="form-label">Notes</label>
            <textarea name="notes" rows="2" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;"></textarea>
        </div>
        <div style="grid-column: 1 / -1; display:flex; justify-content:flex-end;">
            <button class="btn-primary" type="submit">Create Investor</button>
        </div>
    </form>
</div>

<div class="content-card" style="padding:16px;">
    <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>Investors</h3></div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Investor</th>
                    <th style="width:140px;">Number</th>
                    <th style="width:160px;">Phone</th>
                    <th style="width:160px;">Email</th>
                    <th style="width:160px; text-align:right;">Balance</th>
                    <th style="width:120px;">Status</th>
                    <th style="width:140px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($investors as $i)
                    <tr>
                        <td>{{ $i->name }}</td>
                        <td style="font-family:var(--mono); font-weight:800;">{{ $i->investor_number }}</td>
                        <td>{{ $i->phone ?: '—' }}</td>
                        <td>{{ $i->email ?: '—' }}</td>
                        <td style="text-align:right; font-family:var(--mono); font-weight:900;">{{ number_format((float) $i->balance, 2) }}</td>
                        <td>
                            <span class="badge {{ $i->status === 'active' ? 'status-trying' : 'status-pregnant' }}">{{ ucfirst($i->status) }}</span>
                        </td>
                        <td style="text-align:right;">
                            <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.show', $i) }}">Profile</a>
                            <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.edit', $i) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.investors.toggle-status', $i) }}" style="display:inline;">
                                @csrf
                                <button class="btn-icon" type="submit" style="border:0; background:transparent; cursor:pointer;">
                                    {{ $i->status === 'active' ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center; color:#6b7280; padding:18px;">No investors found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $investors->links() }}</div>
</div>
@endsection
