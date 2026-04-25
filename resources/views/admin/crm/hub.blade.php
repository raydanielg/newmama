@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Inbox, feedback, pre-orders, referrals, loyalty and upsell performance.</p>
    </div>
</div>

<div class="stats-grid" style="margin-bottom:14px;">
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">{{ (int) $kpis['open_inbox'] }}</h3><p class="stat-label">Open Inbox</p></div></div>
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">{{ (int) $kpis['open_preorders'] }}</h3><p class="stat-label">Open Pre-Orders</p></div></div>
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">{{ (int) $kpis['pending_referrals'] }}</h3><p class="stat-label">Pending Referrals</p></div></div>
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">{{ (int) $kpis['open_feedback'] }}</h3><p class="stat-label">Open Feedback</p></div></div>
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">{{ (int) $kpis['active_automations'] }}</h3><p class="stat-label">Active Automations</p></div></div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <div style="display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap:12px;">
        <a class="btn-icon" style="text-decoration:none; padding:14px; border:1px solid #e5e7eb; border-radius:12px;" href="{{ route('admin.crm.inbox') }}">Inbox</a>
        <a class="btn-icon" style="text-decoration:none; padding:14px; border:1px solid #e5e7eb; border-radius:12px;" href="{{ route('admin.crm.feedback') }}">Feedback</a>
        <a class="btn-icon" style="text-decoration:none; padding:14px; border:1px solid #e5e7eb; border-radius:12px;" href="{{ route('admin.crm.preorders') }}">Pre-Orders</a>
        <a class="btn-icon" style="text-decoration:none; padding:14px; border:1px solid #e5e7eb; border-radius:12px;" href="{{ route('admin.crm.referrals') }}">Referrals</a>
        <a class="btn-icon" style="text-decoration:none; padding:14px; border:1px solid #e5e7eb; border-radius:12px;" href="{{ route('admin.crm.loyalty') }}">Loyalty</a>
        <a class="btn-icon" style="text-decoration:none; padding:14px; border:1px solid #e5e7eb; border-radius:12px;" href="{{ route('admin.crm.upsell') }}">Upsell</a>
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:14px;">
    <div class="content-card" style="padding:16px;">
        <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>Recent Inbox</h3></div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead><tr><th style="width:110px;">Status</th><th>Subject</th><th style="width:160px;">Customer</th></tr></thead>
                <tbody>
                    @forelse($recentInbox as $m)
                        <tr>
                            <td>{{ $m->status }}</td>
                            <td>{{ $m->subject ?: '—' }}</td>
                            <td>{{ optional($m->customer)->name ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="text-align:center; color:#6b7280; padding:18px;">No messages yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="content-card" style="padding:16px;">
        <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>Recent Feedback</h3></div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead><tr><th style="width:90px;">Rate</th><th>Message</th><th style="width:160px;">Customer</th></tr></thead>
                <tbody>
                    @forelse($recentFeedback as $f)
                        <tr>
                            <td>{{ (int) $f->rating }}/5</td>
                            <td>{{ \Illuminate\Support\Str::limit($f->message, 60) }}</td>
                            <td>{{ optional($f->customer)->name ?: ($f->customer_name ?: '—') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="text-align:center; color:#6b7280; padding:18px;">No feedback yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
