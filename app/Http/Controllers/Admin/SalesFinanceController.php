<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class SalesFinanceController extends Controller
{
    public function dashboard(Request $request)
    {
        $from = $request->query('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->query('to') ?: now()->toDateString();

        $monthlyRevenue = (float) Voucher::query()
            ->where('type', 'sales_invoice')
            ->whereDate('posting_date', '>=', $from)
            ->whereDate('posting_date', '<=', $to)
            ->sum('total_amount');

        $monthlyExpenses = (float) Voucher::query()
            ->where('type', 'cash_payment')
            ->whereDate('posting_date', '>=', $from)
            ->whereDate('posting_date', '<=', $to)
            ->sum('total_amount');

        $cashSales = (float) Voucher::query()
            ->where('type', 'sales_invoice')
            ->where('payment_method', 'cash')
            ->whereDate('posting_date', '>=', $from)
            ->whereDate('posting_date', '<=', $to)
            ->sum('total_amount');

        $netProfit = $monthlyRevenue - $monthlyExpenses;

        $recent = Voucher::query()
            ->whereIn('type', ['sales_invoice', 'cash_receipt', 'cash_payment', 'credit_note'])
            ->orderByDesc('posting_date')
            ->orderByDesc('id')
            ->limit(12)
            ->get();

        return view('admin.erp.sales', [
            'title' => 'Sales & Finance',
            'from' => $from,
            'to' => $to,
            'monthlyRevenue' => $monthlyRevenue,
            'monthlyExpenses' => $monthlyExpenses,
            'cashSales' => $cashSales,
            'netProfit' => $netProfit,
            'recent' => $recent,
        ]);
    }

    public function expenses(Request $request)
    {
        $query = Voucher::query()->where('type', 'cash_payment');

        if ($from = $request->query('from')) {
            $query->whereDate('posting_date', '>=', $from);
        }

        if ($to = $request->query('to')) {
            $query->whereDate('posting_date', '<=', $to);
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('ref', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%");
            });
        }

        $vouchers = $query->orderByDesc('posting_date')->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.erp.expenses', [
            'title' => 'Expense Tracking',
            'vouchers' => $vouchers,
        ]);
    }
}
