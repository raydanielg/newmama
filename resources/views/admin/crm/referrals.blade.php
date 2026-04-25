@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Track referrals and reward approvals.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.crm.hub') }}">Back</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.crm.referrals') }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px;">
        <input name="q" value="{{ request('q') }}" placeholder="Search names/phone" style="flex:1; min-width:220px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <select name="status" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            <option value="">All</option>
            @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $k => $v)
                <option value="{{ $k }}" {{ request('status')===$k?'selected':'' }}>{{ $v }}</option>
            @endforeach
        </select>
        <button class="btn-primary" type="submit">Filter</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.crm.referrals') }}">Reset</a>
    </form>

    <form method="POST" action="{{ route('admin.crm.referrals.store') }}" style="display:grid; grid-template-columns: 260px 1fr 180px 140px; gap:10px; align-items:end;">
        @csrf
        <div>
            <label class="form-label">Referrer (Customer)</label>
            <select name="referrer_customer_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">— Optional —</option>
                @foreach($customers as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Referee Name</label>
            <input name="referee_name" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Referee Phone</label>
            <input name="referee_phone" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Reward</label>
            <input type="number" step="0.01" min="0" name="reward_amount" value="0" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; text-align:right; font-family:var(--mono);">
        </div>
        <div style="grid-column: 1 / -1; display:flex; justify-content:flex-end;">
            <button class="btn-primary" type="submit">Log Referral</button>
        </div>
    </form>
</div>

<div class="content-card" style="padding:16px;">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:120px;">Status</th>
                    <th>Referrer</th>
                    <th>Referee</th>
                    <th style="width:160px;">Phone</th>
                    <th style="width:160px; text-align:right;">Reward</th>
                    <th style="width:170px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($referrals as $r)
                    <tr>
                        <td>{{ $r->status }}</td>
                        <td>{{ optional($r->referrerCustomer)->name ?: ($r->referrer_name ?: '—') }}</td>
                        <td>{{ $r->referee_name }}</td>
                        <td>{{ $r->referee_phone }}</td>
                        <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $r->reward_amount, 2) }}</td>
                        <td style="text-align:right;">
                            <form method="POST" action="{{ route('admin.crm.referrals.status', $r) }}" style="display:inline;">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button class="btn-icon" type="submit">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.crm.referrals.status', $r) }}" style="display:inline;">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button class="btn-icon" type="submit">Reject</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center; color:#6b7280; padding:18px;">No referrals found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $referrals->links() }}</div>
</div>
@endsection
