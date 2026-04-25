<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\VendorLedgerEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SuppliersController extends Controller
{
    public function index(Request $request)
    {
        $filterActive = $request->query('active', 'active');
        $filterActive = in_array($filterActive, ['all', 'active', 'inactive'], true) ? $filterActive : 'active';

        $query = Supplier::query();

        if ($filterActive === 'active') {
            $query->where('is_active', true);
        }

        if ($filterActive === 'inactive') {
            $query->where('is_active', false);
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->orderBy('name')->paginate(15)->withQueryString();

        $totalBalance = (float) Supplier::query()->sum('balance_tzs');
        $filteredBalance = (float) (clone $query)->sum('balance_tzs');

        return view('admin.suppliers.index', compact('suppliers', 'filterActive', 'totalBalance', 'filteredBalance'));
    }

    public function create()
    {
        return view('admin.suppliers.form', [
            'supplier' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'payment_terms' => ['required', 'string', 'max:50'],
        ]);

        $data['code'] = $this->generateCode();
        $data['is_active'] = true;
        $data['balance_tzs'] = 0;
        $data['balance_usd'] = 0;

        $supplier = Supplier::create($data);

        return redirect()->route('admin.suppliers.ledger', $supplier)->with('status', 'Supplier created');
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.form', [
            'supplier' => $supplier,
        ]);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'payment_terms' => ['required', 'string', 'max:50'],
        ]);

        $supplier->update($data);

        return redirect()->route('admin.suppliers.ledger', $supplier)->with('status', 'Supplier updated');
    }

    public function ledger(Request $request, Supplier $supplier)
    {
        $from = $this->parseDate($request->query('from')) ?: now()->subMonths(3)->startOfDay();
        $to = $this->parseDate($request->query('to')) ?: now()->endOfDay();

        $entries = VendorLedgerEntry::query()
            ->where('supplier_id', $supplier->id)
            ->whereDate('posting_date', '>=', $from->toDateString())
            ->whereDate('posting_date', '<=', $to->toDateString())
            ->orderBy('posting_date')
            ->orderBy('id')
            ->get();

        $running = 0.0;
        $entriesWithBalance = $entries->map(function ($e) use (&$running) {
            $amt = (float) $e->amount_tzs;
            if ($amt == 0.0 && (float) $e->amount !== 0.0) {
                $amt = (float) $e->amount;
            }
            $e->_amount = $amt;
            $running += $amt;
            $e->running_balance = $running;
            return $e;
        });

        $openEntries = $entriesWithBalance->filter(fn ($e) => $e->is_open && (float) $e->_amount > 0);
        $importOrderRefs = $entriesWithBalance->pluck('import_order_ref')->filter()->unique()->values();

        return view('admin.suppliers.ledger', [
            'supplier' => $supplier,
            'entries' => $entriesWithBalance,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'openEntriesCount' => $openEntries->count(),
            'importOrderRefs' => $importOrderRefs,
        ]);
    }

    public function statementCsv(Request $request, Supplier $supplier): StreamedResponse
    {
        $from = $this->parseDate($request->query('from')) ?: now()->subMonths(3)->startOfDay();
        $to = $this->parseDate($request->query('to')) ?: now()->endOfDay();

        $entries = VendorLedgerEntry::query()
            ->where('supplier_id', $supplier->id)
            ->whereDate('posting_date', '>=', $from->toDateString())
            ->whereDate('posting_date', '<=', $to->toDateString())
            ->orderBy('posting_date')
            ->orderBy('id')
            ->get();

        $filename = 'vendor_statement_' . $supplier->code . '_' . $from->toDateString() . '_' . $to->toDateString() . '.csv';

        return response()->streamDownload(function () use ($entries) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date', 'Ref', 'Type', 'Description', 'Debit', 'Credit', 'Balance', 'Consignment']);

            $running = 0.0;
            foreach ($entries as $e) {
                $amt = (float) $e->amount_tzs;
                if ($amt == 0.0 && (float) $e->amount !== 0.0) {
                    $amt = (float) $e->amount;
                }
                $running += $amt;

                fputcsv($out, [
                    $e->posting_date?->toDateString(),
                    $e->document_ref,
                    $e->document_type,
                    $e->description,
                    $amt > 0 ? $amt : '',
                    $amt < 0 ? abs($amt) : '',
                    $running,
                    $e->import_order_ref,
                ]);
            }

            fclose($out);
        }, $filename);
    }

    private function generateCode(): string
    {
        $last = Supplier::orderByDesc('code')->value('code');
        $lastNum = 0;
        if ($last) {
            $lastNum = (int) preg_replace('/\D+/', '', $last);
        }

        return 'SUP-' . str_pad((string) ($lastNum + 1), 3, '0', STR_PAD_LEFT);
    }

    private function parseDate(?string $value): ?Carbon
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }
}
