@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-4 bg-teal-soft p-3">
            <svg width="24" height="24" fill="none" stroke="#0d9488" stroke-width="1.8" viewBox="0 0 24 24"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>
        </div>
        <div>
            <h3 class="fw-bold mb-0">Sales Register</h3>
            <p class="text-muted mb-0 small">Product performance, targets & comparisons · <span class="sync-dot"></span> Live</p>
        </div>
    </div>
    <div class="page-actions mt-3 mt-md-0 d-flex gap-2">
        <form method="GET" action="{{ route('admin.sales.register') }}" class="d-flex align-items-center gap-2">
            <input type="date" name="from" value="{{ $from }}" class="form-control form-control-sm" style="width: 140px;">
            <span class="text-muted small">to</span>
            <input type="date" name="to" value="{{ $to }}" class="form-control form-control-sm" style="width: 140px;">
            <select name="category" class="form-select form-select-sm" style="width: 150px;">
                <option value="all">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ $filterCat == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary btn-sm px-3">Load</button>
        </form>
    </div>
</div>

<ul class="nav nav-tabs border-bottom mb-4" id="salesTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active fw-bold small text-uppercase py-3" id="transactions-tab" data-bs-toggle="tab" data-bs-target="#transactions" type="button">Transactions</button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold small text-uppercase py-3" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button">Product Sales</button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold small text-uppercase py-3" id="bundles-tab" data-bs-toggle="tab" data-bs-target="#bundles" type="button">Bundles</button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold small text-uppercase py-3" id="compare-tab" data-bs-toggle="tab" data-bs-target="#compare" type="button">Compare</button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold small text-uppercase py-3" id="targets-tab" data-bs-toggle="tab" data-bs-target="#targets" type="button">Targets</button>
    </li>
</ul>

<div class="tab-content" id="salesTabsContent">
    <div class="tab-pane fade show active" id="transactions" role="tabpanel">
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card green shadow-sm">
                    <div class="stat-label">Total Sales</div>
                    <div class="stat-value">{{ number_format($stats['transaction_count']) }}</div>
                    <div class="stat-change up">Transactions</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card amber shadow-sm">
                    <div class="stat-label">Revenue</div>
                    <div class="stat-value">TZS {{ number_format((float) $stats['total_revenue'], 0) }}</div>
                    <div class="stat-change up">Total</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card blue shadow-sm">
                    <div class="stat-label">Avg Sale</div>
                    <div class="stat-value">TZS {{ number_format((float) $stats['avg_sale'], 0) }}</div>
                    <div class="stat-change up">Per transaction</div>
                </div>
            </div>
        </div>

        <div class="content-card shadow-sm border-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 border-0 text-muted small uppercase fw-bold">Date</th>
                            <th class="py-3 border-0 text-muted small uppercase fw-bold">Reference</th>
                            <th class="py-3 border-0 text-muted small uppercase fw-bold">Customer</th>
                            <th class="py-3 border-0 text-muted small uppercase fw-bold">WhatsApp</th>
                            <th class="py-3 border-0 text-muted small uppercase fw-bold">Payment</th>
                            <th class="py-3 border-0 text-muted small uppercase fw-bold text-end">Total (TZS)</th>
                            <th class="py-3 border-0 text-muted small uppercase fw-bold text-center pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vouchers as $v)
                        <tr>
                            <td class="ps-4 text-muted small">{{ $v->posting_date?->format('Y-m-d') }}</td>
                            <td class="ref-cell">{{ $v->ref }}</td>
                            <td class="fw-bold">{{ $v->customer?->name ?: ($v->description ?: 'Walk-in Customer') }}</td>
                            <td class="text-primary small">{{ $v->customer?->whatsapp ?: '—' }}</td>
                            <td>
                                @php($pm = strtolower((string) ($v->payment_method ?? '')))
                                <span class="badge rounded-pill {{ str_contains($pm, 'cash') ? 'bg-success-soft text-success' : 'bg-info-soft text-info' }}">
                                    {{ $v->payment_method ?: 'N/A' }}
                                </span>
                            </td>
                            <td class="text-end fw-bold text-success">{{ number_format($v->total_amount) }}</td>
                            <td class="text-center pe-4">
                                <span class="badge rounded-pill bg-success-soft text-success">{{ $v->status }}</span>
                            </td>
                        </tr>
                        @endforeach
                        <tr class="bg-light fw-bold">
                            <td colspan="5" class="ps-4">TOTALS</td>
                            <td class="text-end text-success">{{ number_format($stats['total_revenue']) }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $vouchers->links() }}
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="products" role="tabpanel">
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card green shadow-sm">
                    <div class="stat-label">Products Sold</div>
                    <div class="stat-value">{{ number_format((int) ($productTotals['unique_products'] ?? 0)) }}</div>
                    <div class="stat-change up">Unique SKUs</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card amber shadow-sm">
                    <div class="stat-label">Total Units</div>
                    <div class="stat-value">{{ number_format((float) ($productTotals['total_units'] ?? 0), 0) }}</div>
                    <div class="stat-change up">Items sold</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card blue shadow-sm">
                    <div class="stat-label">Revenue</div>
                    <div class="stat-value">TZS {{ number_format((float) ($productTotals['total_revenue'] ?? 0), 0) }}</div>
                    <div class="stat-change up">Product sales</div>
                </div>
            </div>
            <div class="col-md-3">
                @php($rev = (float) ($productTotals['total_revenue'] ?? 0))
                @php($margin = (float) ($productTotals['total_margin'] ?? 0))
                @php($pct = $rev > 0 ? round(($margin / $rev) * 100, 0) : 0)
                <div class="stat-card green shadow-sm">
                    <div class="stat-label">Gross Margin</div>
                    <div class="stat-value">{{ number_format($pct, 0) }}%</div>
                    <div class="stat-change up">TZS {{ number_format($margin, 0) }}</div>
                </div>
            </div>
        </div>

        <div class="content-card shadow-sm border-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 border-0 text-muted small uppercase fw-bold">#</th>
                            <th class="py-3 border-0 text-muted small uppercase fw-bold">SKU</th>
                            <th class="py-3 border-0 text-muted small uppercase fw-bold">Product</th>
                            <th class="py-3 border-0 text-muted small uppercase fw-bold">Category</th>
                            <th class="py-3 border-0 text-muted small uppercase fw-bold text-end">Units</th>
                            <th class="py-3 border-0 text-muted small uppercase fw-bold text-end">Revenue</th>
                            <th class="py-3 border-0 text-muted small uppercase fw-bold text-end">Cost</th>
                            <th class="py-3 border-0 text-muted small uppercase fw-bold text-end">Margin</th>
                            <th class="py-3 border-0 text-muted small uppercase fw-bold text-end">Avg Price</th>
                            <th class="py-3 border-0 text-muted small uppercase fw-bold text-end pe-4">Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productRows as $i => $r)
                        <tr>
                            <td class="ps-4 text-muted small">{{ $i + 1 }}</td>
                            <td class="fw-bold text-warning">{{ $r['sku'] }}</td>
                            <td class="fw-bold">{{ $r['name'] }}</td>
                            <td class="text-muted small">{{ $r['category'] }}</td>
                            <td class="text-end">{{ number_format((float) $r['units_sold'], 0) }}</td>
                            <td class="text-end fw-bold text-success">{{ number_format((float) $r['revenue'], 0) }}</td>
                            <td class="text-end text-muted">{{ number_format((float) $r['cost'], 0) }}</td>
                            <td class="text-end">{{ number_format((float) $r['margin_pct'], 0) }}%</td>
                            <td class="text-end">{{ number_format((float) $r['avg_price'], 0) }}</td>
                            <td class="text-end pe-4 text-muted">{{ number_format((int) $r['tx_count']) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">No product sales found for this period.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="tab-pane fade" id="bundles" role="tabpanel">
        <div class="content-card shadow-sm border-0 overflow-hidden">
            <div class="card-header bg-white border-bottom-0 p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold m-0">Bundles</h5>
                    <div class="text-muted small">Track bundle performance with clean references across periods.</div>
                </div>
                <div class="ref-pill">Reference: <span class="ref-cell">N/A</span></div>
            </div>
            <div class="p-4 pt-0">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="stat-card blue shadow-sm h-100">
                            <div class="stat-label">Bundles Sold</div>
                            <div class="stat-value">0</div>
                            <div class="stat-change">By date range</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card amber shadow-sm h-100">
                            <div class="stat-label">Revenue</div>
                            <div class="stat-value">TZS 0</div>
                            <div class="stat-change">Bundle totals</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card green shadow-sm h-100">
                            <div class="stat-label">Top Bundle</div>
                            <div class="stat-value">N/A</div>
                            <div class="stat-change">Highest revenue</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 border-0 text-muted small uppercase fw-bold">Reference</th>
                                    <th class="py-3 border-0 text-muted small uppercase fw-bold">Bundle</th>
                                    <th class="py-3 border-0 text-muted small uppercase fw-bold text-end">Units</th>
                                    <th class="py-3 border-0 text-muted small uppercase fw-bold text-end pe-4">Revenue (TZS)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        No bundle sales found for this period.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="compare" role="tabpanel">
        <div class="content-card shadow-sm border-0 overflow-hidden">
            <div class="card-header bg-white border-bottom-0 p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold m-0">Compare</h5>
                    <div class="text-muted small">Compare performance by date ranges, category, or product lines.</div>
                </div>
                <div class="ref-pill">Reference: <span class="ref-cell">N/A</span></div>
            </div>
            <div class="p-4 pt-0">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="content-card shadow-sm border-0 p-4 h-100" style="background:rgba(13, 148, 136, 0.06);">
                            <div class="fw-bold">Period A</div>
                            <div class="text-muted small mt-1">Select a date range to compare</div>
                            <div class="mt-3 d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge rounded-pill bg-info-soft text-info">Revenue</span>
                                <span class="badge rounded-pill bg-success-soft text-success">Units</span>
                                <span class="badge rounded-pill" style="background:rgba(245,158,11,0.12); color:#b45309;">Margin</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="content-card shadow-sm border-0 p-4 h-100" style="background:rgba(91, 58, 34, 0.06);">
                            <div class="fw-bold">Period B</div>
                            <div class="text-muted small mt-1">Select a date range to compare</div>
                            <div class="mt-3 d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge rounded-pill bg-info-soft text-info">Revenue</span>
                                <span class="badge rounded-pill bg-success-soft text-success">Units</span>
                                <span class="badge rounded-pill" style="background:rgba(245,158,11,0.12); color:#b45309;">Margin</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 border-0 text-muted small uppercase fw-bold">Metric</th>
                                    <th class="py-3 border-0 text-muted small uppercase fw-bold">Period A</th>
                                    <th class="py-3 border-0 text-muted small uppercase fw-bold">Period B</th>
                                    <th class="py-3 border-0 text-muted small uppercase fw-bold pe-4">Reference</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        Pick two periods to compare.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="targets" role="tabpanel">
        <div class="content-card shadow-sm border-0 overflow-hidden">
            <div class="card-header bg-white border-bottom-0 p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold m-0">Targets</h5>
                    <div class="text-muted small">Set and monitor monthly targets with clear references per update.</div>
                </div>
                <div class="ref-pill">Reference: <span class="ref-cell">N/A</span></div>
            </div>
            <div class="p-4 pt-0">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="stat-card green shadow-sm h-100">
                            <div class="stat-label">Target Revenue</div>
                            <div class="stat-value">TZS 0</div>
                            <div class="stat-change">Monthly</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card blue shadow-sm h-100">
                            <div class="stat-label">Actual Revenue</div>
                            <div class="stat-value">TZS 0</div>
                            <div class="stat-change">MTD</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card amber shadow-sm h-100">
                            <div class="stat-label">Progress</div>
                            <div class="stat-value">0%</div>
                            <div class="stat-change">Against target</div>
                        </div>
                    </div>
                </div>

                <div class="content-card shadow-sm border-0 p-4 mt-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="fw-bold">Monthly progress</div>
                        <div class="text-muted small">Actual vs Target</div>
                    </div>
                    <div class="progress mt-3" style="height:10px; border-radius:999px; background:rgba(15, 23, 42, 0.08);">
                        <div class="progress-bar" role="progressbar" style="width:0%; border-radius:999px; background:#0d9488;"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="text-muted small">0% achieved</div>
                        <div class="text-muted small">Reference: <span class="ref-cell">N/A</span></div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 border-0 text-muted small uppercase fw-bold">Month</th>
                                    <th class="py-3 border-0 text-muted small uppercase fw-bold text-end">Target (TZS)</th>
                                    <th class="py-3 border-0 text-muted small uppercase fw-bold text-end">Actual (TZS)</th>
                                    <th class="py-3 border-0 text-muted small uppercase fw-bold pe-4">Reference</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        No targets set yet.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-teal-soft { background-color: rgba(13, 148, 136, 0.1); }
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
    .bg-info-soft { background-color: rgba(13, 202, 240, 0.1); }
    .sync-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #10b981;
        border-radius: 50%;
        margin-right: 4px;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    .fw-black { font-weight: 900; }
    .nav-tabs .nav-link { border: none; color: #6b7280; border-bottom: 2px solid transparent; }
    .nav-tabs .nav-link.active { color: #0d9488; border-bottom: 2px solid #0d9488; background: none; }
    .stat-card { background: #fff; border-radius: 16px; padding: 18px 18px; border: 1px solid rgba(15, 23, 42, 0.06); }
    .stat-label { font-size: 12px; font-weight: 700; color: rgba(15, 23, 42, 0.55); text-transform: uppercase; letter-spacing: .5px; }
    .stat-value { margin-top: 6px; font-size: 28px; font-weight: 900; color: rgba(15, 23, 42, 0.92); }
    .stat-change { margin-top: 6px; font-size: 12px; font-weight: 700; color: rgba(15, 23, 42, 0.55); }
    .stat-card.green { box-shadow: 0 10px 25px rgba(16, 185, 129, 0.08); }
    .stat-card.amber { box-shadow: 0 10px 25px rgba(245, 158, 11, 0.08); }
    .stat-card.blue { box-shadow: 0 10px 25px rgba(14, 165, 233, 0.08); }
    .ref-cell { font-weight: 900; color: #b45309; font-family: var(--mono); white-space: nowrap; }
    .ref-pill { display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; background:rgba(15, 23, 42, 0.04); border:1px solid rgba(15, 23, 42, 0.06); font-size:12px; color:rgba(15, 23, 42, 0.65); }
    @media (max-width: 576px) {
        .ref-cell { font-size: 12px; }
        .ref-pill { padding:6px 10px; }
    }
</style>
@endsection
