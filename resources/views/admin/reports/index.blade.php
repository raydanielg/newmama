@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<style>
    .stats-grid .stat-card{min-width:0;}
    .stats-grid .stat-details{min-width:0;}
    .stats-grid .stat-value{overflow:hidden; text-overflow:ellipsis; white-space:nowrap;}
    .stats-grid .stat-label{overflow:hidden; text-overflow:ellipsis; white-space:nowrap;}
    @media (max-width: 576px){
        .stats-grid .stat-value{font-size:18px;}
        .stats-grid .stat-label{font-size:12px;}
    }
</style>
<div class="module-header">
    <div class="header-info">
        <div class="d-flex align-items-center gap-3">
            <h3 class="fw-bold mb-0">{{ $title }}</h3>
            <div id="refresh-status" class="badge bg-success-soft text-success small border-0 py-2">
                <i class="bi bi-arrow-repeat spin-on-load"></i> Auto-refreshing
            </div>
        </div>
        <p class="text-muted">Financial statements and operational insight dashboard.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="content-card shadow-sm border-0 p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0">Sales Trend (Last 6 Months)</h5>
                <div class="text-muted small">TSh (Millions)</div>
            </div>
            <div style="height: 300px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="content-card shadow-sm border-0 p-4 h-100">
            <h5 class="fw-bold mb-4">Quick Filter</h5>
            <form method="GET" action="{{ route('admin.reports') }}" class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-bold">From Date</label>
                    <input type="date" name="from" value="{{ $from }}" class="form-control form-control-lg border-light bg-light">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold">To Date</label>
                    <input type="date" name="to" value="{{ $to }}" class="form-control form-control-lg border-light bg-light">
                </div>
                <div class="col-12 pt-2">
                    <button class="btn btn-primary w-100 py-3 fw-bold" type="submit">Update Dashboard</button>
                    <a class="btn btn-link w-100 mt-2 text-decoration-none text-muted small" href="{{ route('admin.reports') }}">Reset Dates</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="stats-grid mb-4">
    <div class="stat-card border-0 shadow-sm">
        <div class="stat-details">
            <p class="stat-label uppercase small fw-bold text-muted">Sales Revenue</p>
            <h3 class="stat-value fw-black text-dark" id="kpi-sales">TSh {{ number_format((float) $kpis['sales'], 0) }}</h3>
            <span class="text-success small fw-bold">↑ Actual Invoices</span>
        </div>
    </div>
    <div class="stat-card border-0 shadow-sm">
        <div class="stat-details">
            <p class="stat-label uppercase small fw-bold text-muted">Total Receipts</p>
            <h3 class="stat-value fw-black text-success" id="kpi-receipts">TSh {{ number_format((float) $kpis['receipts'], 0) }}</h3>
            <span class="text-muted small">Cash/Bank Inflow</span>
        </div>
    </div>
    <div class="stat-card border-0 shadow-sm">
        <div class="stat-details">
            <p class="stat-label uppercase small fw-bold text-muted">Total Payments</p>
            <h3 class="stat-value fw-black text-danger" id="kpi-payments">TSh {{ number_format((float) $kpis['payments'], 0) }}</h3>
            <span class="text-muted small">Operational Outflow</span>
        </div>
    </div>
</div>

<div class="stats-grid mb-4">
    <div class="stat-card border-0 shadow-sm bg-light">
        <div class="stat-details">
            <p class="stat-label uppercase small fw-bold text-muted">Receivables (AR)</p>
            <h3 class="stat-value fw-black" id="kpi-open_ar">TSh {{ number_format((float) $kpis['open_ar'], 0) }}</h3>
            <span class="text-indigo small">Unpaid by Customers</span>
        </div>
    </div>
    <div class="stat-card border-0 shadow-sm bg-light">
        <div class="stat-details">
            <p class="stat-label uppercase small fw-bold text-muted">Payables (AP)</p>
            <h3 class="stat-value fw-black" id="kpi-open_ap">TSh {{ number_format((float) $kpis['open_ap'], 0) }}</h3>
            <span class="text-orange small">Owed to Suppliers</span>
        </div>
    </div>
    <div class="stat-card border-0 shadow-sm bg-light">
        <div class="stat-details">
            <p class="stat-label uppercase small fw-bold text-muted">Stock Valuation</p>
            <h3 class="stat-value fw-black" id="kpi-stock_value">TSh {{ number_format((float) $kpis['stock_value'], 0) }}</h3>
            <span class="text-muted small">Inventory Asset Value</span>
        </div>
    </div>
</div>

<div class="row g-3">
    @php
        $reportLinks = [
            ['Profit & Loss', 'pnl', 'blue'],
            ['Trial Balance', 'trial-balance', 'indigo'],
            ['Balance Sheet', 'balance-sheet', 'purple'],
            ['AR Aging', 'ar-aging', 'cyan'],
            ['AP Aging', 'ap-aging', 'orange'],
            ['Stock Valuation', 'stock-valuation', 'teal'],
            ['Purchase Register', 'purchase-register', 'pink'],
            ['Payment Register', 'payment-register', 'emerald'],
            ['Stock Transfers', 'stock-transfer-register', 'slate'],
        ];
    @endphp

    @foreach($reportLinks as $link)
    <div class="col-md-4">
        <a href="{{ route('admin.reports.' . $link[1], ($link[1] !== 'balance-sheet' && $link[1] !== 'ar-aging' && $link[1] !== 'ap-aging' && $link[1] !== 'stock-valuation') ? ['from' => $from, 'to' => $to] : []) }}" 
           class="content-card shadow-sm border-0 p-4 d-flex align-items-center gap-3 text-decoration-none hover-scale transition h-100">
            <div class="rounded-circle bg-{{ $link[2] ?? 'primary' }}-soft p-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-{{ $link[2] ?? 'primary' }}"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <div>
                <h6 class="fw-bold m-0 text-dark">{{ $link[0] }}</h6>
                <small class="text-muted">Generate detailed statement</small>
            </div>
        </a>
    </div>
    @endforeach

    <div class="col-md-4">
        <a href="{{ route('admin.sales.register', ['from' => $from, 'to' => $to]) }}"
           class="content-card shadow-sm border-0 p-4 d-flex align-items-center gap-3 text-decoration-none hover-scale transition h-100">
            <div class="rounded-circle bg-emerald-soft p-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
            </div>
            <div>
                <h6 class="fw-bold m-0 text-dark">Sales Register</h6>
                <small class="text-muted">Transactions, product performance</small>
            </div>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        let salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['labels']) !!},
                datasets: [{
                    label: 'Monthly Sales',
                    data: {!! json_encode($chartData['values']) !!},
                    borderColor: '#f82249',
                    backgroundColor: 'rgba(248, 34, 73, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#f82249',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: {
                            callback: function(value) {
                                return value >= 1000000 ? (value / 1000000) + 'M' : value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Auto-refresh functionality
        function refreshData() {
            const statusEl = document.getElementById('refresh-status');
            statusEl.classList.add('refreshing');
            
            fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update KPIs
                document.getElementById('kpi-sales').innerText = 'TSh ' + Math.round(data.kpis.sales).toLocaleString();
                document.getElementById('kpi-receipts').innerText = 'TSh ' + Math.round(data.kpis.receipts).toLocaleString();
                document.getElementById('kpi-payments').innerText = 'TSh ' + Math.round(data.kpis.payments).toLocaleString();
                document.getElementById('kpi-open_ar').innerText = 'TSh ' + Math.round(data.kpis.open_ar).toLocaleString();
                document.getElementById('kpi-open_ap').innerText = 'TSh ' + Math.round(data.kpis.open_ap).toLocaleString();
                document.getElementById('kpi-stock_value').innerText = 'TSh ' + Math.round(data.kpis.stock_value).toLocaleString();

                // Update Chart
                salesChart.data.labels = data.chartData.labels;
                salesChart.data.datasets[0].data = data.chartData.values;
                salesChart.update();

                setTimeout(() => statusEl.classList.remove('refreshing'), 1000);
            })
            .catch(error => {
                console.error('Refresh failed:', error);
                statusEl.classList.remove('refreshing');
            });
        }

        // Refresh every 30 seconds
        setInterval(refreshData, 30000);
    });
</script>

<style>
    .hover-scale:hover { transform: translateY(-3px); }
    .transition { transition: all 0.2s ease-in-out; }
    .fw-black { font-weight: 900; }
    .bg-blue-soft { background-color: #e0f2fe; }
    .text-blue { color: #0284c7; }
    .bg-indigo-soft { background-color: #e0e7ff; }
    .text-indigo { color: #4f46e5; }
    .bg-purple-soft { background-color: #f3e8ff; }
    .text-purple { color: #9333ea; }
    .bg-cyan-soft { background-color: #ecfeff; }
    .text-cyan { color: #0891b2; }
    .bg-orange-soft { background-color: #fff7ed; }
    .text-orange { color: #ea580c; }
    .bg-teal-soft { background-color: #f0fdf4; }
    .text-teal { color: #0d9488; }
    .bg-pink-soft { background-color: #fdf2f8; }
    .text-pink { color: #db2777; }
    .bg-emerald-soft { background-color: #ecfdf5; }
    .text-emerald { color: #059669; }
    .bg-slate-soft { background-color: #f8fafc; }
    .text-slate { color: #475569; }
    .bg-success-soft { background-color: rgba(16, 185, 129, 0.1); }
    .refreshing { opacity: 0.5; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    .spin-on-load { animation: spin 2s linear infinite; }
</style>
@endsection
