<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmInboxMessage;
use App\Models\Employee;
use App\Models\Investor;
use App\Models\InvestorTransaction;
use App\Models\LoginActivity;
use App\Models\Mother;
use App\Models\PayrollRun;
use App\Models\SystemSetting;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $from = now()->subDays(13)->startOfDay();

        $kpis = [
            'mothers_total' => (int) Mother::query()->count(),
            'mothers_today' => (int) Mother::query()->whereDate('created_at', $today)->count(),

            'investors_total' => (int) Investor::query()->count(),
            'investors_active' => (int) Investor::query()->where('status', 'active')->count(),
            'investors_total_balance' => (float) Investor::query()->sum('balance'),

            'sales_mtd_revenue' => (float) Voucher::query()
                ->where('type', 'sales_invoice')
                ->whereBetween('posting_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
                ->sum('total_amount'),
            'sales_mtd_payments' => (float) Voucher::query()
                ->where('type', 'cash_receipt')
                ->whereBetween('posting_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
                ->sum('total_amount'),

            'hr_active_employees' => (int) Employee::query()->where('employment_status', 'active')->count(),
            'hr_monthly_basic_payroll' => (float) Employee::query()->where('employment_status', 'active')->sum('basic_salary'),

            'crm_open_inbox' => (int) CrmInboxMessage::query()->where('status', 'open')->count(),
        ];

        $recent = [
            'mothers' => Mother::query()->orderByDesc('created_at')->limit(6)->get(),
            'payments' => Voucher::query()->where('type', 'cash_receipt')->orderByDesc('posting_date')->orderByDesc('id')->limit(8)->get(),
            'investor_txns' => InvestorTransaction::query()->with('investor')->orderByDesc('posting_date')->orderByDesc('id')->limit(8)->get(),
            'crm_inbox' => CrmInboxMessage::query()->with(['customer', 'assignee'])->orderByDesc('created_at')->limit(8)->get(),
            'payroll_runs' => PayrollRun::query()->orderByDesc('processed_at')->orderByDesc('id')->limit(6)->get(),
            'logins' => LoginActivity::query()->with('user')->orderByDesc('logged_at')->limit(10)->get(),
        ];

        $days = collect(range(0, 13))
            ->map(fn ($i) => Carbon::parse($from)->addDays($i)->toDateString())
            ->values()
            ->all();

        $countsByDay = [
            'mothers' => Mother::query()
                ->where('created_at', '>=', $from)
                ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
                ->groupBy('d')
                ->pluck('c', 'd')
                ->toArray(),
            'payments' => Voucher::query()
                ->where('type', 'cash_receipt')
                ->whereDate('posting_date', '>=', $from->toDateString())
                ->selectRaw('posting_date as d, COUNT(*) as c')
                ->groupBy('d')
                ->pluck('c', 'd')
                ->toArray(),
            'investor_txns' => InvestorTransaction::query()
                ->whereDate('posting_date', '>=', $from->toDateString())
                ->selectRaw('posting_date as d, COUNT(*) as c')
                ->groupBy('d')
                ->pluck('c', 'd')
                ->toArray(),
            'crm_inbox' => CrmInboxMessage::query()
                ->where('created_at', '>=', $from)
                ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
                ->groupBy('d')
                ->pluck('c', 'd')
                ->toArray(),
        ];

        $line = [
            'labels' => $days,
            'series' => [
                'mothers' => array_map(fn ($d) => (int) ($countsByDay['mothers'][$d] ?? 0), $days),
                'payments' => array_map(fn ($d) => (int) ($countsByDay['payments'][$d] ?? 0), $days),
                'investor_txns' => array_map(fn ($d) => (int) ($countsByDay['investor_txns'][$d] ?? 0), $days),
                'crm_inbox' => array_map(fn ($d) => (int) ($countsByDay['crm_inbox'][$d] ?? 0), $days),
            ],
        ];

        $pie = [
            'labels' => ['Sales Invoices', 'Payments', 'Investor Txns', 'CRM Inbox'],
            'values' => [
                (int) Voucher::query()->where('type', 'sales_invoice')->whereDate('posting_date', '>=', $from->toDateString())->count(),
                (int) Voucher::query()->where('type', 'cash_receipt')->whereDate('posting_date', '>=', $from->toDateString())->count(),
                (int) InvestorTransaction::query()->whereDate('posting_date', '>=', $from->toDateString())->count(),
                (int) CrmInboxMessage::query()->where('created_at', '>=', $from)->count(),
            ],
        ];

        $license = SystemSetting::query()
            ->whereIn('key', ['license.status', 'license.expires_at', 'license.plan'])
            ->pluck('value', 'key')
            ->toArray();

        return view('admin.dashboard', [
            'title' => 'Dashboard',
            'kpis' => $kpis,
            'recent' => $recent,
            'line' => $line,
            'pie' => $pie,
            'license' => $license,
        ]);
    }
}
