@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Payslip</h3>
        <p>{{ optional($payslip->employee)->full_name }} · {{ optional($payslip->payrollRun)->period }}</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.hrm.employee.show', $payslip->employee) }}">Employee</a>
        <button class="btn-primary" onclick="window.print()">Print</button>
    </div>
</div>

<div class="stats-grid" style="margin-bottom:14px;">
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">{{ number_format((float) $payslip->basic_salary, 2) }}</h3><p class="stat-label">Basic</p></div></div>
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">{{ number_format((float) $payslip->total_earnings, 2) }}</h3><p class="stat-label">Earnings</p></div></div>
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">{{ number_format((float) $payslip->total_deductions, 2) }}</h3><p class="stat-label">Deductions</p></div></div>
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">{{ number_format((float) $payslip->net_pay, 2) }}</h3><p class="stat-label">Net Pay</p></div></div>
</div>

<div class="content-card" style="padding:16px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
        <div style="font-weight:900;">Lines</div>
        <div style="color:#6b7280;">Status: <span class="badge status-trying">{{ ucfirst($payslip->status) }}</span></div>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Component</th>
                    <th style="width:120px;">Type</th>
                    <th style="width:180px; text-align:right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payslip->lines as $l)
                    <tr>
                        <td>{{ $l->component_name }}</td>
                        <td>{{ strtoupper($l->type) }}</td>
                        <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $l->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" style="text-align:center; color:#6b7280; padding:18px;">No lines.</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" style="text-align:right;">Net Pay</th>
                    <th style="text-align:right; font-family:var(--mono); font-weight:900;">{{ number_format((float) $payslip->net_pay, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
