<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\CustomerLedgerEntry;
use App\Models\Journal;
use App\Models\JournalLine;
use App\Models\Product;
use App\Models\VendorLedgerEntry;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getKpiData($request);
        }

        $from = $request->query('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->query('to') ?: now()->toDateString();

        $data = $this->getKpiData($request);

        return view('admin.reports.index', array_merge([
            'title' => 'Reports',
            'from' => $from,
            'to' => $to,
        ], $data));
    }

    private function getKpiData(Request $request)
    {
        $from = $request->query('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->query('to') ?: now()->toDateString();

        $sales = (float) Voucher::query()
            ->where('type', 'sales_invoice')
            ->whereDate('posting_date', '>=', $from)
            ->whereDate('posting_date', '<=', $to)
            ->sum('total_amount');

        $receipts = (float) Voucher::query()
            ->where('type', 'cash_receipt')
            ->whereDate('posting_date', '>=', $from)
            ->whereDate('posting_date', '<=', $to)
            ->sum('total_amount');

        $payments = (float) Voucher::query()
            ->where('type', 'cash_payment')
            ->whereDate('posting_date', '>=', $from)
            ->whereDate('posting_date', '<=', $to)
            ->sum('total_amount');

        $purchases = (float) Voucher::query()
            ->where('type', 'purchase_invoice')
            ->whereDate('posting_date', '>=', $from)
            ->whereDate('posting_date', '<=', $to)
            ->sum('total_amount');

        $openAr = (float) CustomerLedgerEntry::query()->where('is_open', true)->sum('remaining_amount');
        $openAp = (float) VendorLedgerEntry::query()->where('is_open', true)->sum('remaining_amount');

        $stockValue = (float) Product::query()->sum(DB::raw('qty_on_hand * cost_price'));

        // Monthly Sales Chart Data
        $monthlySales = Voucher::query()
            ->where('type', 'sales_invoice')
            ->where('posting_date', '>=', now()->subMonths(6)->startOfMonth())
            ->select(DB::raw("DATE_FORMAT(posting_date, '%Y-%m') as month"), DB::raw('SUM(total_amount) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $data = [
            'kpis' => [
                'sales' => $sales,
                'receipts' => $receipts,
                'payments' => $payments,
                'purchases' => $purchases,
                'open_ar' => $openAr,
                'open_ap' => $openAp,
                'stock_value' => $stockValue,
            ],
            'chartData' => [
                'labels' => $monthlySales->pluck('month'),
                'values' => $monthlySales->pluck('total'),
            ]
        ];

        if ($request->ajax()) {
            return response()->json($data);
        }

        return $data;
    }

    public function pnl(Request $request)
    {
        $from = $request->query('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->query('to') ?: now()->toDateString();

        $base = JournalLine::query()
            ->select('accounts.id as account_id', 'accounts.code', 'accounts.name', 'accounts.type', DB::raw('SUM(journal_lines.debit) as debit'), DB::raw('SUM(journal_lines.credit) as credit'))
            ->join('accounts', 'accounts.id', '=', 'journal_lines.account_id')
            ->join('journals', 'journals.id', '=', 'journal_lines.journal_id')
            ->where('journals.status', 'posted')
            ->whereDate('journals.posting_date', '>=', $from)
            ->whereDate('journals.posting_date', '<=', $to)
            ->whereIn('accounts.type', ['revenue', 'expense', 'cogs'])
            ->groupBy('accounts.id', 'accounts.code', 'accounts.name', 'accounts.type')
            ->orderBy('accounts.type')
            ->orderBy('accounts.code');

        $rows = $base->get()->map(function ($r) {
            $debit = (float) $r->debit;
            $credit = (float) $r->credit;
            $amount = $r->type === 'revenue' ? ($credit - $debit) : ($debit - $credit);
            return [
                'id' => $r->account_id,
                'code' => $r->code,
                'name' => $r->name,
                'type' => $r->type,
                'amount' => (float) $amount,
            ];
        });

        $revenue = (float) $rows->where('type', 'revenue')->sum('amount');
        $cogs = (float) $rows->where('type', 'cogs')->sum('amount');
        $expense = (float) $rows->where('type', 'expense')->sum('amount');
        $gross = $revenue - $cogs;
        $net = $gross - $expense;

        return view('admin.reports.pnl', [
            'title' => 'Profit & Loss',
            'from' => $from,
            'to' => $to,
            'rows' => $rows,
            'totals' => compact('revenue', 'cogs', 'expense', 'gross', 'net'),
        ]);
    }

    public function trialBalance(Request $request)
    {
        $from = $request->query('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->query('to') ?: now()->toDateString();

        $rows = JournalLine::query()
            ->select('accounts.id as account_id', 'accounts.code', 'accounts.name', 'accounts.type', DB::raw('SUM(journal_lines.debit) as debit'), DB::raw('SUM(journal_lines.credit) as credit'))
            ->join('accounts', 'accounts.id', '=', 'journal_lines.account_id')
            ->join('journals', 'journals.id', '=', 'journal_lines.journal_id')
            ->where('journals.status', 'posted')
            ->whereDate('journals.posting_date', '>=', $from)
            ->whereDate('journals.posting_date', '<=', $to)
            ->groupBy('accounts.id', 'accounts.code', 'accounts.name', 'accounts.type')
            ->orderBy('accounts.code')
            ->get();

        $totalDebit = (float) $rows->sum('debit');
        $totalCredit = (float) $rows->sum('credit');

        return view('admin.reports.trial_balance', [
            'title' => 'Trial Balance',
            'from' => $from,
            'to' => $to,
            'rows' => $rows,
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit,
        ]);
    }

    public function balanceSheet(Request $request)
    {
        $asOf = $request->query('as_of') ?: now()->toDateString();

        $movement = JournalLine::query()
            ->select('accounts.id as account_id', 'accounts.code', 'accounts.name', 'accounts.type', DB::raw('SUM(journal_lines.debit) as debit'), DB::raw('SUM(journal_lines.credit) as credit'))
            ->join('accounts', 'accounts.id', '=', 'journal_lines.account_id')
            ->join('journals', 'journals.id', '=', 'journal_lines.journal_id')
            ->where('journals.status', 'posted')
            ->whereDate('journals.posting_date', '<=', $asOf)
            ->whereIn('accounts.type', ['asset', 'liability', 'equity'])
            ->groupBy('accounts.id', 'accounts.code', 'accounts.name', 'accounts.type')
            ->orderBy('accounts.type')
            ->orderBy('accounts.code')
            ->get()
            ->map(function ($r) {
                $debit = (float) $r->debit;
                $credit = (float) $r->credit;
                $balance = $r->type === 'asset' ? ($debit - $credit) : ($credit - $debit);
                return [
                    'code' => $r->code,
                    'name' => $r->name,
                    'type' => $r->type,
                    'balance' => (float) $balance,
                ];
            });

        $assets = $movement->where('type', 'asset')->values();
        $liabilities = $movement->where('type', 'liability')->values();
        $equity = $movement->where('type', 'equity')->values();

        $totalAssets = (float) $assets->sum('balance');
        $totalLiabilities = (float) $liabilities->sum('balance');
        $totalEquity = (float) $equity->sum('balance');

        return view('admin.reports.balance_sheet', [
            'title' => 'Balance Sheet',
            'asOf' => $asOf,
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'totalAssets' => $totalAssets,
            'totalLiabilities' => $totalLiabilities,
            'totalEquity' => $totalEquity,
        ]);
    }

    public function arAging(Request $request)
    {
        $asOf = $request->query('as_of') ?: now()->toDateString();

        $rows = CustomerLedgerEntry::query()
            ->with('customer')
            ->where('is_open', true)
            ->whereDate('posting_date', '<=', $asOf)
            ->orderBy('due_date')
            ->get();

        $aged = $rows->map(function ($e) use ($asOf) {
            $due = optional($e->due_date)->toDateString();
            $days = 0;
            if ($due) {
                $days = (int) Carbon::parse($due)->diffInDays(Carbon::parse($asOf), false);
                $days = $days < 0 ? 0 : $days;
            }

            $bucket = 'current';
            if ($days >= 1 && $days <= 30) {
                $bucket = '1_30';
            } elseif ($days >= 31 && $days <= 60) {
                $bucket = '31_60';
            } elseif ($days >= 61 && $days <= 90) {
                $bucket = '61_90';
            } elseif ($days > 90) {
                $bucket = '90_plus';
            }

            return [
                'customer' => $e->customer,
                'document_ref' => $e->document_ref,
                'posting_date' => $e->posting_date,
                'due_date' => $e->due_date,
                'remaining' => (float) $e->remaining_amount,
                'bucket' => $bucket,
            ];
        });

        $totals = [
            'current' => (float) $aged->where('bucket', 'current')->sum('remaining'),
            '1_30' => (float) $aged->where('bucket', '1_30')->sum('remaining'),
            '31_60' => (float) $aged->where('bucket', '31_60')->sum('remaining'),
            '61_90' => (float) $aged->where('bucket', '61_90')->sum('remaining'),
            '90_plus' => (float) $aged->where('bucket', '90_plus')->sum('remaining'),
        ];

        $totals['total'] = array_sum($totals);

        return view('admin.reports.ar_aging', [
            'title' => 'AR Aging',
            'asOf' => $asOf,
            'rows' => $aged,
            'totals' => $totals,
        ]);
    }

    public function apAging(Request $request)
    {
        $asOf = $request->query('as_of') ?: now()->toDateString();

        $rows = VendorLedgerEntry::query()
            ->with('supplier')
            ->where('is_open', true)
            ->whereDate('posting_date', '<=', $asOf)
            ->orderBy('due_date')
            ->get();

        $aged = $rows->map(function ($e) use ($asOf) {
            $due = optional($e->due_date)->toDateString();
            $days = 0;
            if ($due) {
                $days = (int) Carbon::parse($due)->diffInDays(Carbon::parse($asOf), false);
                $days = $days < 0 ? 0 : $days;
            }

            $bucket = 'current';
            if ($days >= 1 && $days <= 30) {
                $bucket = '1_30';
            } elseif ($days >= 31 && $days <= 60) {
                $bucket = '31_60';
            } elseif ($days >= 61 && $days <= 90) {
                $bucket = '61_90';
            } elseif ($days > 90) {
                $bucket = '90_plus';
            }

            return [
                'supplier' => $e->supplier,
                'document_ref' => $e->document_ref,
                'posting_date' => $e->posting_date,
                'due_date' => $e->due_date,
                'remaining' => (float) $e->remaining_amount,
                'bucket' => $bucket,
            ];
        });

        $totals = [
            'current' => (float) $aged->where('bucket', 'current')->sum('remaining'),
            '1_30' => (float) $aged->where('bucket', '1_30')->sum('remaining'),
            '31_60' => (float) $aged->where('bucket', '31_60')->sum('remaining'),
            '61_90' => (float) $aged->where('bucket', '61_90')->sum('remaining'),
            '90_plus' => (float) $aged->where('bucket', '90_plus')->sum('remaining'),
        ];

        $totals['total'] = array_sum($totals);

        return view('admin.reports.ap_aging', [
            'title' => 'AP Aging',
            'asOf' => $asOf,
            'rows' => $aged,
            'totals' => $totals,
        ]);
    }

    public function stockValuation(Request $request)
    {
        $products = Product::query()->where('is_active', true)->orderBy('sku')->get();

        $rows = $products->map(function ($p) {
            $qty = (float) $p->qty_on_hand;
            $cost = (float) $p->cost_price;
            return [
                'sku' => $p->sku,
                'name' => $p->name,
                'qty' => $qty,
                'cost' => $cost,
                'value' => $qty * $cost,
            ];
        });

        $total = (float) $rows->sum('value');

        return view('admin.reports.stock_valuation', [
            'title' => 'Stock Valuation',
            'rows' => $rows,
            'total' => $total,
        ]);
    }

    public function purchaseRegister(Request $request)
    {
        $from = $request->query('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->query('to') ?: now()->toDateString();

        $query = Voucher::query()->whereIn('type', ['purchase_invoice', 'purchase_return']);

        $query->whereDate('posting_date', '>=', $from)->whereDate('posting_date', '<=', $to);

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('ref', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $vouchers = $query->orderByDesc('posting_date')->orderByDesc('id')->paginate(20)->withQueryString();

        $total = (float) (clone $query)->sum('total_amount');

        return view('admin.reports.purchase_register', [
            'title' => 'Purchase Register',
            'from' => $from,
            'to' => $to,
            'q' => $request->query('q', ''),
            'vouchers' => $vouchers,
            'total' => $total,
        ]);
    }

    public function paymentRegister(Request $request)
    {
        $from = $request->query('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->query('to') ?: now()->toDateString();

        $query = Voucher::query()->whereIn('type', ['cash_receipt', 'cash_payment', 'bank_transfer', 'contra_entry']);

        $query->whereDate('posting_date', '>=', $from)->whereDate('posting_date', '<=', $to);

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('ref', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%");
            });
        }

        $vouchers = $query->orderByDesc('posting_date')->orderByDesc('id')->paginate(20)->withQueryString();

        $total = (float) (clone $query)->sum('total_amount');

        return view('admin.reports.payment_register', [
            'title' => 'Payment Register',
            'from' => $from,
            'to' => $to,
            'q' => $request->query('q', ''),
            'vouchers' => $vouchers,
            'total' => $total,
        ]);
    }

    public function stockTransferRegister(Request $request)
    {
        $from = $request->query('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->query('to') ?: now()->toDateString();

        $query = Voucher::query()->where('type', 'stock_transfer');
        $query->whereDate('posting_date', '>=', $from)->whereDate('posting_date', '<=', $to);

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('ref', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $vouchers = $query->orderByDesc('posting_date')->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.reports.stock_transfer_register', [
            'title' => 'Stock Transfer Register',
            'from' => $from,
            'to' => $to,
            'q' => $request->query('q', ''),
            'vouchers' => $vouchers,
        ]);
    }
}
