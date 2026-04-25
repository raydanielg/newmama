@extends('layouts.admin')

@section('title', $title)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
<style>
    .kpi-card{display:flex; gap:12px; align-items:center;}
    .kpi-ico{width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex:0 0 auto;}
    .kpi-ico i{font-size:18px;}
    .stats-grid .stat-card{min-width:0;}
    .stats-grid .stat-details{min-width:0;}
    .stats-grid .stat-value{margin:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;}
    .stats-grid .stat-label{margin:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;}
    @media (max-width: 576px){
        .kpi-card{gap:10px;}
        .kpi-ico{width:40px; height:40px; border-radius:12px;}
        .stats-grid .stat-value{font-size:18px;}
        .stats-grid .stat-label{font-size:12px;}
    }
</style>
@endpush

@section('admin-content')
<div class="stats-grid" style="margin-bottom:14px; display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:12px; align-items:stretch;">
    <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay:40ms;"><div class="kpi-card"><div class="kpi-ico" style="background:rgba(37,99,235,0.12); color:#2563eb;"><i class="fa-solid fa-person-pregnant"></i></div><div class="stat-details"><h3 class="stat-value">{{ (int) $kpis['mothers_total'] }}</h3><p class="stat-label">Mothers Total</p></div></div></div>
    <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay:80ms;"><div class="kpi-card"><div class="kpi-ico" style="background:rgba(22,163,74,0.12); color:#16a34a;"><i class="fa-solid fa-calendar-day"></i></div><div class="stat-details"><h3 class="stat-value">{{ (int) $kpis['mothers_today'] }}</h3><p class="stat-label">New Mothers Today</p></div></div></div>
    <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay:120ms;"><div class="kpi-card"><div class="kpi-ico" style="background:rgba(245,158,11,0.12); color:#f59e0b;"><i class="fa-solid fa-users"></i></div><div class="stat-details"><h3 class="stat-value">{{ (int) $kpis['investors_total'] }}</h3><p class="stat-label">Investors</p></div></div></div>
    <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay:160ms;"><div class="kpi-card"><div class="kpi-ico" style="background:rgba(168,85,247,0.12); color:#a855f7;"><i class="fa-solid fa-user-check"></i></div><div class="stat-details"><h3 class="stat-value">{{ (int) $kpis['investors_active'] }}</h3><p class="stat-label">Active Investors</p></div></div></div>
    <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay:200ms;"><div class="kpi-card"><div class="kpi-ico" style="background:rgba(2,132,199,0.12); color:#0284c7;"><i class="fa-solid fa-wallet"></i></div><div class="stat-details"><h3 class="stat-value">TSh {{ number_format((float) $kpis['investors_total_balance'], 0) }}</h3><p class="stat-label">Investor Balances</p></div></div></div>
    <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay:240ms;"><div class="kpi-card"><div class="kpi-ico" style="background:rgba(234,88,12,0.12); color:#ea580c;"><i class="fa-solid fa-file-invoice"></i></div><div class="stat-details"><h3 class="stat-value">TSh {{ number_format((float) $kpis['sales_mtd_revenue'], 0) }}</h3><p class="stat-label">Sales (MTD)</p></div></div></div>
    <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay:280ms;"><div class="kpi-card"><div class="kpi-ico" style="background:rgba(20,184,166,0.12); color:#14b8a6;"><i class="fa-solid fa-money-bill-wave"></i></div><div class="stat-details"><h3 class="stat-value">TSh {{ number_format((float) $kpis['sales_mtd_payments'], 0) }}</h3><p class="stat-label">Payments (MTD)</p></div></div></div>
    <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay:320ms;"><div class="kpi-card"><div class="kpi-ico" style="background:rgba(99,102,241,0.12); color:#6366f1;"><i class="fa-solid fa-people-group"></i></div><div class="stat-details"><h3 class="stat-value">{{ (int) $kpis['hr_active_employees'] }}</h3><p class="stat-label">Active Employees</p></div></div></div>
    <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay:360ms;"><div class="kpi-card"><div class="kpi-ico" style="background:rgba(239,68,68,0.10); color:#ef4444;"><i class="fa-solid fa-coins"></i></div><div class="stat-details"><h3 class="stat-value">TSh {{ number_format((float) $kpis['hr_monthly_basic_payroll'], 0) }}</h3><p class="stat-label">Monthly Basic Payroll</p></div></div></div>
    <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay:400ms;"><div class="kpi-card"><div class="kpi-ico" style="background:rgba(34,197,94,0.10); color:#22c55e;"><i class="fa-solid fa-inbox"></i></div><div class="stat-details"><h3 class="stat-value">{{ (int) $kpis['crm_open_inbox'] }}</h3><p class="stat-label">CRM Open Inbox</p></div></div></div>
</div>

<div style="display:grid; grid-template-columns: 1.25fr 0.75fr; gap:14px; margin-bottom:14px;">
    <div class="content-card animate__animated animate__fadeIn" style="padding:16px; animation-delay:120ms;">
        <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>Activity Trend (Last 14 Days)</h3></div>
        <div style="position:relative; height:260px;">
            <canvas id="lineChart" style="display:block; width:100%; height:100%;"></canvas>
        </div>
    </div>

    <div class="content-card animate__animated animate__fadeIn" style="padding:16px; animation-delay:160ms;">
        <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>Distribution</h3></div>
        <div style="position:relative; height:260px;">
            <canvas id="pieChart" style="display:block; width:100%; height:100%;"></canvas>
        </div>
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:14px; margin-bottom:14px;">
    <div class="content-card animate__animated animate__fadeInUp" style="padding:16px; animation-delay:80ms;">
        <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>Recent Payments</h3></div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead><tr><th style="width:120px;">Date</th><th>Ref</th><th>Description</th><th style="width:160px; text-align:right;">Amount</th></tr></thead>
                <tbody>
                    @forelse($recent['payments'] as $p)
                        <tr>
                            <td style="font-family:var(--mono);">{{ optional($p->posting_date)->toDateString() }}</td>
                            <td style="font-family:var(--mono); font-weight:800;">{{ $p->ref }}</td>
                            <td>{{ $p->description ?: '—' }}</td>
                            <td style="text-align:right; font-family:var(--mono); font-weight:800;">{{ number_format((float) $p->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center; color:#6b7280; padding:18px;">No payments.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="content-card animate__animated animate__fadeInUp" style="padding:16px; animation-delay:120ms;">
        <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>Recent Investor Transactions</h3></div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead><tr><th style="width:120px;">Date</th><th>Investor</th><th style="width:140px;">Type</th><th style="width:160px; text-align:right;">Amount</th></tr></thead>
                <tbody>
                    @forelse($recent['investor_txns'] as $t)
                        <tr>
                            <td style="font-family:var(--mono);">{{ optional($t->posting_date)->toDateString() }}</td>
                            <td>{{ optional($t->investor)->name ?: '—' }}</td>
                            <td>{{ strtoupper($t->type) }}</td>
                            <td style="text-align:right; font-family:var(--mono); font-weight:800;">{{ number_format((float) $t->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center; color:#6b7280; padding:18px;">No transactions.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:14px;">
    <div class="content-card animate__animated animate__fadeInUp" style="padding:16px; animation-delay:120ms;">
        <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>CRM Inbox (Latest)</h3></div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead><tr><th>Subject</th><th style="width:120px;">Status</th><th style="width:160px;">Assignee</th><th style="width:140px;">Created</th></tr></thead>
                <tbody>
                    @forelse($recent['crm_inbox'] as $m)
                        <tr>
                            <td>{{ $m->subject ?: '—' }}</td>
                            <td><span class="badge {{ $m->status === 'open' ? 'status-trying' : 'status-pregnant' }}">{{ strtoupper($m->status) }}</span></td>
                            <td>{{ optional($m->assignee)->name ?: '—' }}</td>
                            <td style="font-family:var(--mono);">{{ optional($m->created_at)->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center; color:#6b7280; padding:18px;">No inbox messages.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="content-card animate__animated animate__fadeInUp" style="padding:16px; animation-delay:160ms;">
        <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>Recent Logins</h3></div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead><tr><th>User</th><th style="width:160px;">IP</th><th style="width:160px;">When</th></tr></thead>
                <tbody>
                    @forelse($recent['logins'] as $l)
                        <tr>
                            <td>{{ optional($l->user)->name ?: '—' }}</td>
                            <td style="font-family:var(--mono);">{{ $l->ip_address ?: '—' }}</td>
                            <td style="font-family:var(--mono);">{{ optional($l->logged_at)->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="text-align:center; color:#6b7280; padding:18px;">No login activity yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
const lineDataRaw = @json($line);
const pieDataRaw = @json($pie);

const lineData = (lineDataRaw && typeof lineDataRaw === 'object') ? lineDataRaw : { labels: [], series: { mothers: [], payments: [], investor_txns: [], crm_inbox: [] } };
const pieData = (pieDataRaw && typeof pieDataRaw === 'object') ? pieDataRaw : { labels: [], values: [] };

const lineEl = document.getElementById('lineChart');
if (lineEl && window.Chart && Array.isArray(lineData.labels) && lineData.series) {
    new Chart(lineEl, {
        type: 'line',
        data: {
            labels: lineData.labels,
            datasets: [
                { label: 'Mothers', data: (lineData.series.mothers || []), borderColor: '#2563eb', backgroundColor: 'rgba(37,99,235,0.15)', tension: 0.35, fill: true },
                { label: 'Payments', data: (lineData.series.payments || []), borderColor: '#16a34a', backgroundColor: 'rgba(22,163,74,0.12)', tension: 0.35, fill: true },
                { label: 'Investor Txns', data: (lineData.series.investor_txns || []), borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,0.12)', tension: 0.35, fill: true },
                { label: 'CRM Inbox', data: (lineData.series.crm_inbox || []), borderColor: '#a855f7', backgroundColor: 'rgba(168,85,247,0.10)', tension: 0.35, fill: true },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
}

const pieEl = document.getElementById('pieChart');
if (pieEl && window.Chart && Array.isArray(pieData.labels) && Array.isArray(pieData.values)) {
    const values = pieData.values.map(v => Number(v) || 0);
    const total = values.reduce((a, b) => a + b, 0);

    const normalized = total > 0
        ? { labels: pieData.labels, values }
        : { labels: ['No data'], values: [1] };

    const palette = ['#2563eb', '#16a34a', '#f59e0b', '#a855f7'];
    const colors = normalized.labels.length === 1 && normalized.labels[0] === 'No data'
        ? ['#e5e7eb']
        : normalized.labels.map((_, i) => palette[i % palette.length]);

    new Chart(pieEl, {
        type: 'doughnut',
        data: {
            labels: normalized.labels,
            datasets: [{
                data: normalized.values,
                backgroundColor: colors,
                borderWidth: 0,
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom' } },
            cutout: '62%'
        }
    });
}
</script>
@endsection
