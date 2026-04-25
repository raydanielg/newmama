<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesPagesController extends Controller
{
    public function salesInvoice()
    {
        return redirect()->route('admin.vouchers.sales-invoice.create');
    }

    public function creditNote()
    {
        return redirect()->route('admin.vouchers.credit-note.create');
    }

    public function cashSale()
    {
        return redirect()->route('admin.vouchers.sales-invoice.create');
    }

    public function dayBook(Request $request)
    {
        $query = Voucher::query()->whereIn('type', ['sales_invoice']);

        if ($from = $request->query('from')) {
            $query->whereDate('posting_date', '>=', $from);
        }

        if ($to = $request->query('to')) {
            $query->whereDate('posting_date', '<=', $to);
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('ref', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $vouchers = $query->orderByDesc('posting_date')->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.sales.day_book', [
            'title' => 'Sales Day Book',
            'vouchers' => $vouchers,
        ]);
    }

    public function register(Request $request)
    {
        $from = $request->query('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->query('to') ?: now()->toDateString();
        $filterCat = $request->query('category', 'all');

        $query = Voucher::query()
            ->with(['customer', 'lines.product'])
            ->whereIn('type', ['cash_sale', 'sales_invoice'])
            ->where('status', 'posted')
            ->whereDate('posting_date', '>=', $from)
            ->whereDate('posting_date', '<=', $to);

        if ($filterCat !== 'all') {
            $query->whereHas('lines.product', function ($q) use ($filterCat) {
                $q->where('category', $filterCat);
            });
        }

        $allSales = $query->orderByDesc('posting_date')->orderByDesc('id')->get();
        
        // Pagination for the main list
        $vouchers = $query->paginate(50)->withQueryString();

        $totalRevenue = $allSales->sum('total_amount');
        $transactionCount = $allSales->count();
        $avgSale = $transactionCount > 0 ? $totalRevenue / $transactionCount : 0;

        $categories = Product::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->values();

        $productMap = [];
        $productTxSeen = [];
        foreach ($allSales as $sale) {
            foreach ($sale->lines as $line) {
                $product = $line->product;
                if (!$product) {
                    continue;
                }

                if ($filterCat !== 'all' && (string) $product->category !== (string) $filterCat) {
                    continue;
                }

                $key = (string) $product->id;
                if (!isset($productMap[$key])) {
                    $productMap[$key] = [
                        'product_id' => $product->id,
                        'sku' => $product->sku,
                        'name' => $product->name,
                        'category' => $product->category,
                        'units_sold' => 0.0,
                        'revenue' => 0.0,
                        'cost' => 0.0,
                        'tx_count' => 0,
                    ];
                    $productTxSeen[$key] = [];
                }

                $qty = (float) $line->qty;
                $revenue = (float) ($line->total ?? 0);
                if ($revenue === 0.0) {
                    $revenue = $qty * (float) ($line->unit_price ?? 0);
                }
                $cost = $qty * (float) ($line->unit_cost ?? 0);

                $productMap[$key]['units_sold'] += $qty;
                $productMap[$key]['revenue'] += $revenue;
                $productMap[$key]['cost'] += $cost;

                $saleKey = (string) $sale->id;
                if (!isset($productTxSeen[$key][$saleKey])) {
                    $productTxSeen[$key][$saleKey] = true;
                    $productMap[$key]['tx_count'] += 1;
                }
            }
        }

        $productRows = collect(array_values($productMap))
            ->map(function (array $r) {
                $revenue = (float) $r['revenue'];
                $cost = (float) $r['cost'];
                $units = (float) $r['units_sold'];
                $margin = $revenue - $cost;
                $r['margin'] = $margin;
                $r['margin_pct'] = $revenue > 0 ? round(($margin / $revenue) * 100, 0) : 0;
                $r['avg_price'] = $units > 0 ? round($revenue / $units, 0) : 0;
                return $r;
            })
            ->sortByDesc('revenue')
            ->values();

        $productTotals = [
            'unique_products' => (int) $productRows->count(),
            'total_units' => (float) $productRows->sum('units_sold'),
            'total_revenue' => (float) $productRows->sum('revenue'),
            'total_cost' => (float) $productRows->sum('cost'),
            'total_margin' => (float) $productRows->sum('margin'),
        ];

        return view('admin.sales.register', [
            'title' => 'Sales Register',
            'vouchers' => $vouchers,
            'allSales' => $allSales,
            'from' => $from,
            'to' => $to,
            'categories' => $categories,
            'filterCat' => $filterCat,
            'stats' => [
                'total_revenue' => $totalRevenue,
                'transaction_count' => $transactionCount,
                'avg_sale' => $avgSale,
            ]
            ,'productRows' => $productRows,
            'productTotals' => $productTotals,
        ]);
    }

    public function salesReturn(Request $request)
    {
        $query = Voucher::query()->whereIn('type', ['credit_note']);

        if ($from = $request->query('from')) {
            $query->whereDate('posting_date', '>=', $from);
        }

        if ($to = $request->query('to')) {
            $query->whereDate('posting_date', '<=', $to);
        }

        $vouchers = $query->orderByDesc('posting_date')->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.sales.sales_return', [
            'title' => 'Sales Return',
            'vouchers' => $vouchers,
        ]);
    }

    public function quotation()
    {
        $customers = Customer::query()->where('is_active', true)->orderBy('name')->get();

        $vouchers = Voucher::query()
            ->where('type', 'quotation')
            ->orderByDesc('posting_date')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.sales.quotation', [
            'title' => 'Quotation',
            'customers' => $customers,
            'vouchers' => $vouchers,
            'nextRef' => $this->nextRef('QT-'),
        ]);
    }

    public function quotationStore(Request $request)
    {
        $data = $request->validate([
            'posting_date' => ['required', 'date'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'description' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'numeric', 'min:0'],
        ]);

        $voucher = Voucher::create([
            'ref' => $this->nextRef('QT-'),
            'type' => 'quotation',
            'posting_date' => $data['posting_date'],
            'description' => $data['description'],
            'total_amount' => (float) $data['total_amount'],
            'status' => 'draft',
            'customer_id' => $data['customer_id'] ?? null,
        ]);

        return redirect()->route('admin.sales.quotation')->with('status', 'Quotation saved: ' . $voucher->ref);
    }

    public function debitNote()
    {
        $customers = Customer::query()->where('is_active', true)->orderBy('name')->get();

        $vouchers = Voucher::query()
            ->where('type', 'debit_note')
            ->orderByDesc('posting_date')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.sales.debit_note', [
            'title' => 'Debit Note',
            'customers' => $customers,
            'vouchers' => $vouchers,
            'nextRef' => $this->nextRef('DN-'),
        ]);
    }

    public function debitNoteStore(Request $request)
    {
        $data = $request->validate([
            'posting_date' => ['required', 'date'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'description' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'numeric', 'min:0'],
        ]);

        $voucher = Voucher::create([
            'ref' => $this->nextRef('DN-'),
            'type' => 'debit_note',
            'posting_date' => $data['posting_date'],
            'description' => $data['description'],
            'total_amount' => (float) $data['total_amount'],
            'status' => 'draft',
            'customer_id' => $data['customer_id'] ?? null,
        ]);

        return redirect()->route('admin.sales.debit-note')->with('status', 'Debit Note saved: ' . $voucher->ref);
    }

    private function nextRef(string $prefix): string
    {
        $last = Voucher::query()
            ->where('ref', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('ref');

        $n = 1;
        if (is_string($last) && preg_match('/(\d+)$/', $last, $m)) {
            $n = ((int) $m[1]) + 1;
        }

        return $prefix . str_pad((string) $n, 5, '0', STR_PAD_LEFT);
    }
}
