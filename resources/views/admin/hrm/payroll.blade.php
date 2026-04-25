@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Setup earnings & deductions then generate payroll runs and payslips.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.employees') }}">Employees</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <div style="font-weight:800; margin-bottom:10px;">Payroll Setup (Components)</div>
    <form method="POST" action="{{ route('admin.hrm.payroll.components.store') }}" style="display:grid; grid-template-columns: 1fr 160px 180px 140px 140px 140px; gap:10px; align-items:end;">
        @csrf
        <div>
            <label class="form-label">Name</label>
            <input name="name" required placeholder="e.g. Transport Allowance" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Type</label>
            <select name="type" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="earning">Earning</option>
                <option value="deduction">Deduction</option>
            </select>
        </div>
        <div>
            <label class="form-label">Calc</label>
            <select name="calculation_type" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="fixed">Fixed</option>
                <option value="percent_basic">% of Basic</option>
            </select>
        </div>
        <div>
            <label class="form-label">Amount</label>
            <input type="number" step="0.01" min="0" name="amount" value="0" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; text-align:right; font-family:var(--mono);">
        </div>
        <div>
            <label class="form-label">Rate (%)</label>
            <input type="number" step="0.01" min="0" name="rate" value="0" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; text-align:right; font-family:var(--mono);">
        </div>
        <div>
            <button class="btn-primary" type="submit">Add</button>
        </div>
    </form>

    <div class="table-responsive" style="margin-top:14px;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th style="width:120px;">Type</th>
                    <th style="width:160px;">Calc</th>
                    <th style="width:160px; text-align:right;">Amount</th>
                    <th style="width:120px; text-align:right;">Rate</th>
                    <th style="width:110px;">Active</th>
                    <th style="width:120px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($components as $c)
                    <tr>
                        <td>{{ $c->name }}</td>
                        <td>{{ strtoupper($c->type) }}</td>
                        <td style="font-family:var(--mono);">{{ $c->calculation_type }}</td>
                        <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $c->amount, 2) }}</td>
                        <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $c->rate, 2) }}</td>
                        <td>{{ $c->is_active ? 'Yes' : 'No' }}</td>
                        <td style="text-align:right;">
                            <form method="POST" action="{{ route('admin.hrm.payroll.components.toggle', $c) }}" style="display:inline;">
                                @csrf
                                <button class="btn-icon" type="submit">Toggle</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center; color:#6b7280; padding:18px;">No components yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <div style="font-weight:800; margin-bottom:10px;">Run Payroll</div>
    <form method="POST" action="{{ route('admin.hrm.payroll.run') }}" style="display:flex; gap:10px; flex-wrap:wrap; align-items:end;">
        @csrf
        <div>
            <label class="form-label">Period Start</label>
            <input type="date" name="period_start" value="{{ now()->startOfMonth()->toDateString() }}" required style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Period End</label>
            <input type="date" name="period_end" value="{{ now()->endOfMonth()->toDateString() }}" required style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <button class="btn-primary" type="submit">Generate Payslips</button>
    </form>
</div>

<div class="content-card" style="padding:16px;">
    <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>Payroll Runs</h3></div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:120px;">Period</th>
                    <th style="width:140px;">Start</th>
                    <th style="width:140px;">End</th>
                    <th style="width:110px; text-align:right;">Employees</th>
                    <th style="width:160px; text-align:right;">Total Basic</th>
                    <th style="width:160px; text-align:right;">Total Deductions</th>
                    <th style="width:160px; text-align:right;">Net Pay</th>
                    <th style="width:100px;">Status</th>
                    <th style="width:120px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($runs as $r)
                    <tr>
                        <td style="font-family:var(--mono); font-weight:700;">{{ $r->period }}</td>
                        <td>{{ optional($r->period_start)->toDateString() }}</td>
                        <td>{{ optional($r->period_end)->toDateString() }}</td>
                        <td style="text-align:right; font-family:var(--mono);">{{ (int) $r->employee_count }}</td>
                        <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $r->total_basic, 2) }}</td>
                        <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $r->total_deductions, 2) }}</td>
                        <td style="text-align:right; font-family:var(--mono); font-weight:800;">{{ number_format((float) $r->total_net, 2) }}</td>
                        <td>{{ $r->status }}</td>
                        <td style="text-align:right;">
                            <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.payroll') }}?period={{ $r->period }}">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" style="text-align:center; color:#6b7280; padding:18px;">No payroll runs yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $runs->links() }}</div>
</div>
@endsection
