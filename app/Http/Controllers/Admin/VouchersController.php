<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Customer;
use App\Models\CustomerLedgerEntry;
use App\Models\Journal;
use App\Models\JournalLine;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\VendorLedgerEntry;
use App\Models\Voucher;
use App\Models\VoucherLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VouchersController extends Controller
{
    public function index()
    {
        return view('admin.vouchers.index');
    }

    public function view(Voucher $voucher)
    {
        $voucher->load(['customer', 'supplier', 'lines.product', 'journal.lines.account']);

        return view('admin.vouchers.view', [
            'voucher' => $voucher,
        ]);
    }

    public function salesInvoiceCreate()
    {
        $customers = Customer::query()
            ->where('is_active', true)
            ->where('customer_type', 'debtor')
            ->orderBy('name')
            ->get();

        $products = Product::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.vouchers.sales_invoice', [
            'ref' => $this->nextRef('sales_invoice'),
            'customers' => $customers,
            'products' => $products,
        ]);
    }

    public function salesInvoiceStore(Request $request)
    {
        $data = $request->validate([
            'posting_date' => ['required', 'date'],
            'ref' => ['required', 'string', 'max:50'],
            'customer_id' => ['required', 'exists:customers,id'],
            'due_date' => ['nullable', 'date'],
            'payment_terms' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:255'],

            'lines' => ['required', 'array', 'min:1'],
            'lines.*.product_id' => ['required', 'exists:products,id'],
            'lines.*.qty' => ['required', 'numeric', 'min:0.01'],
            'lines.*.unit_price' => ['required', 'numeric', 'min:0'],
            'lines.*.discount_pct' => ['nullable', 'numeric', 'min:0'],
        ]);

        $customer = Customer::findOrFail($data['customer_id']);

        $productsById = Product::query()
            ->whereIn('id', collect($data['lines'])->pluck('product_id')->all())
            ->get()
            ->keyBy('id');

        $lines = collect($data['lines'])->map(function ($l) use ($productsById) {
            $prod = $productsById->get((int) $l['product_id']);
            $qty = (float) $l['qty'];
            $unitPrice = (float) $l['unit_price'];
            $disc = (float) ($l['discount_pct'] ?? 0);
            $amount = round($unitPrice * $qty * (1 - ($disc / 100)), 2);
            $vat = round($amount * 18 / 118, 2);

            return [
                'product_id' => (int) $l['product_id'],
                'qty' => $qty,
                'unit_cost' => $prod ? (float) $prod->cost_price : 0,
                'unit_price' => $unitPrice,
                'discount_pct' => $disc,
                'subtotal' => $amount,
                'vat_amount' => $vat,
                'total' => $amount,
                'description' => $prod ? $prod->name : 'Item',
            ];
        })->filter(fn ($l) => $l['total'] > 0)->values();

        if ($lines->count() === 0) {
            return back()->withErrors(['lines' => 'Please add at least one valid line'])->withInput();
        }

        foreach ($lines as $l) {
            $prod = $productsById->get($l['product_id']);
            if ($prod && (float) $prod->qty_on_hand < (float) $l['qty']) {
                return back()->withErrors(['lines' => 'Insufficient stock for ' . $prod->name])->withInput();
            }
        }

        $subtotal = (float) $lines->sum('total');
        $vat = (float) $lines->sum('vat_amount');
        $netRevenue = (float) ($subtotal - $vat);
        $cogsTotal = (float) $lines->sum(fn ($l) => (float) $l['unit_cost'] * (float) $l['qty']);

        $ar = Account::where('code', '1050')->first();
        $revenue = Account::where('code', '4011')->first();
        $vatOut = Account::where('code', '2020')->first();
        $cogs = Account::where('code', '5010')->first();
        $inventory = Account::where('code', '1110')->first();

        if (!$ar || !$revenue || !$vatOut || !$cogs || !$inventory) {
            return back()->withErrors(['customer_id' => 'Required GL accounts not found. Run seeders.'])->withInput();
        }

        DB::transaction(function () use ($data, $customer, $productsById, $lines, $subtotal, $vat, $netRevenue, $cogsTotal, $ar, $revenue, $vatOut, $cogs, $inventory) {
            $journal = Journal::create([
                'ref' => 'JV-' . $data['ref'],
                'posting_date' => $data['posting_date'],
                'description' => 'Sales Invoice — ' . $customer->name . ' — ' . $data['ref'],
                'journal_type' => 'sales_invoice',
                'source_type' => 'sales_invoice',
                'source_ref' => $data['ref'],
                'posted_by' => optional(auth()->user())->name,
                'status' => 'posted',
                'branch' => null,
            ]);

            $jLines = [
                ['account' => $ar, 'desc' => 'AR — ' . $customer->name . ' — ' . $data['ref'], 'debit' => $subtotal, 'credit' => 0],
                ['account' => $revenue, 'desc' => 'Revenue — ' . $data['ref'], 'debit' => 0, 'credit' => $netRevenue],
                ['account' => $vatOut, 'desc' => 'VAT — ' . $data['ref'], 'debit' => 0, 'credit' => $vat],
                ['account' => $cogs, 'desc' => 'COGS — ' . $data['ref'], 'debit' => $cogsTotal, 'credit' => 0],
                ['account' => $inventory, 'desc' => 'Inventory out — ' . $data['ref'], 'debit' => 0, 'credit' => $cogsTotal],
            ];

            foreach ($jLines as $idx => $l) {
                JournalLine::create([
                    'journal_id' => $journal->id,
                    'line_number' => $idx + 1,
                    'account_id' => $l['account']->id,
                    'description' => $l['desc'],
                    'debit' => (float) $l['debit'],
                    'credit' => (float) $l['credit'],
                ]);

                Account::whereKey($l['account']->id)->update([
                    'balance' => DB::raw('balance + ' . (float) $l['debit'] . ' - ' . (float) $l['credit']),
                ]);
            }

            $voucher = Voucher::create([
                'ref' => $data['ref'],
                'type' => 'sales_invoice',
                'posting_date' => $data['posting_date'],
                'due_date' => $data['due_date'] ?? null,
                'payment_terms' => $data['payment_terms'] ?? null,
                'description' => 'Sales Invoice — ' . $customer->name,
                'subtotal' => $netRevenue,
                'vat_amount' => $vat,
                'total_amount' => $subtotal,
                'status' => 'posted',
                'customer_id' => $customer->id,
                'journal_id' => $journal->id,
                'notes' => $data['notes'] ?? null,
                'posted_by' => optional(auth()->user())->name,
            ]);

            foreach ($lines as $idx => $l) {
                VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'line_number' => $idx + 1,
                    'product_id' => $l['product_id'],
                    'description' => $l['description'],
                    'qty' => $l['qty'],
                    'unit_cost' => $l['unit_cost'],
                    'unit_price' => $l['unit_price'],
                    'discount_pct' => $l['discount_pct'],
                    'vat_amount' => $l['vat_amount'],
                    'subtotal' => $l['subtotal'],
                    'total' => $l['total'],
                ]);

                $prod = $productsById->get($l['product_id']);
                if ($prod) {
                    $newQty = (float) $prod->qty_on_hand - (float) $l['qty'];
                    Product::whereKey($prod->id)->update(['qty_on_hand' => $newQty]);
                }
            }

            $customer->balance = (float) $customer->balance + $subtotal;
            $customer->last_purchase_date = $data['posting_date'];
            $customer->last_purchase_amount = $subtotal;
            $customer->save();

            CustomerLedgerEntry::create([
                'customer_id' => $customer->id,
                'posting_date' => $data['posting_date'],
                'document_type' => 'invoice',
                'document_ref' => $data['ref'],
                'description' => 'Sales Invoice — ' . $customer->name,
                'amount' => $subtotal,
                'remaining_amount' => $subtotal,
                'due_date' => $data['due_date'] ?? null,
                'is_open' => true,
            ]);
        });

        return redirect()->route('admin.vouchers')->with('status', $data['ref'] . ' posted');
    }

    public function creditNoteCreate()
    {
        $customers = Customer::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.vouchers.credit_note', [
            'ref' => $this->nextRef('credit_note'),
            'customers' => $customers,
        ]);
    }

    public function creditNoteStore(Request $request)
    {
        $data = $request->validate([
            'posting_date' => ['required', 'date'],
            'ref' => ['required', 'string', 'max:50'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'original_inv' => ['nullable', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'reason' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $amount = (float) $data['amount'];

        $revenue = Account::where('code', '4010')->first();
        $ar = Account::where('code', '1050')->first();
        if (!$revenue || !$ar) {
            return back()->withErrors(['amount' => 'Revenue (4010) or AR (1050) account not found. Run seeders.'])->withInput();
        }

        $customer = null;
        if (!empty($data['customer_id'])) {
            $customer = Customer::find($data['customer_id']);
        }

        DB::transaction(function () use ($data, $amount, $revenue, $ar, $customer) {
            $journal = Journal::create([
                'ref' => 'JV-' . $data['ref'],
                'posting_date' => $data['posting_date'],
                'description' => 'Credit Note — ' . $data['customer_name'] . ' — ' . $data['ref'],
                'journal_type' => 'credit_note',
                'source_type' => 'credit_note',
                'source_ref' => $data['ref'],
                'posted_by' => optional(auth()->user())->name,
                'status' => 'posted',
                'branch' => null,
            ]);

            JournalLine::create([
                'journal_id' => $journal->id,
                'line_number' => 1,
                'account_id' => $revenue->id,
                'description' => 'Revenue reduced — ' . $data['reason'],
                'debit' => $amount,
                'credit' => 0,
            ]);

            JournalLine::create([
                'journal_id' => $journal->id,
                'line_number' => 2,
                'account_id' => $ar->id,
                'description' => 'AR reduced — ' . $data['customer_name'] . ' — ' . $data['ref'],
                'debit' => 0,
                'credit' => $amount,
            ]);

            Account::whereKey($revenue->id)->update([
                'balance' => DB::raw('balance + ' . $amount . ' - 0'),
            ]);

            Account::whereKey($ar->id)->update([
                'balance' => DB::raw('balance + 0 - ' . $amount),
            ]);

            $notes = trim(($data['reason'] ?? '')
                . (!empty($data['original_inv']) ? ' · Orig: ' . $data['original_inv'] : '')
                . (!empty($data['notes']) ? ' ' . $data['notes'] : ''));

            Voucher::create([
                'ref' => $data['ref'],
                'type' => 'credit_note',
                'posting_date' => $data['posting_date'],
                'description' => 'Credit Note — ' . $data['customer_name'],
                'total_amount' => $amount,
                'status' => 'posted',
                'journal_id' => $journal->id,
                'customer_id' => $customer?->id,
                'notes' => $notes ?: null,
                'posted_by' => optional(auth()->user())->name,
            ]);

            if ($customer) {
                $customer->balance = (float) $customer->balance - $amount;
                $customer->save();

                CustomerLedgerEntry::create([
                    'customer_id' => $customer->id,
                    'posting_date' => $data['posting_date'],
                    'document_type' => 'credit_note',
                    'document_ref' => $data['ref'],
                    'description' => 'Credit Note — ' . $data['reason'],
                    'amount' => -$amount,
                    'remaining_amount' => -$amount,
                    'is_open' => true,
                    'due_date' => null,
                ]);
            }
        });

        return redirect()->route('admin.vouchers')->with('status', $data['ref'] . ' posted');
    }

    public function contraEntryCreate()
    {
        $accounts = Account::query()
            ->where('is_active', true)
            ->where('category', 'Cash & Bank')
            ->orderBy('code')
            ->get();

        return view('admin.vouchers.contra_entry', [
            'ref' => $this->nextRef('contra'),
            'accounts' => $accounts,
        ]);
    }

    public function contraEntryStore(Request $request)
    {
        $data = $request->validate([
            'posting_date' => ['required', 'date'],
            'ref' => ['required', 'string', 'max:50'],
            'from_account_id' => ['required', 'exists:accounts,id'],
            'to_account_id' => ['required', 'exists:accounts,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        if ((string) $data['from_account_id'] === (string) $data['to_account_id']) {
            return back()->withErrors(['to_account_id' => 'Source and destination cannot be the same'])->withInput();
        }

        $amount = (float) $data['amount'];
        $fromAcct = Account::findOrFail($data['from_account_id']);
        $toAcct = Account::findOrFail($data['to_account_id']);

        DB::transaction(function () use ($data, $amount, $fromAcct, $toAcct) {
            $journal = Journal::create([
                'ref' => 'JV-' . $data['ref'],
                'posting_date' => $data['posting_date'],
                'description' => 'Contra — ' . $fromAcct->name . ' → ' . $toAcct->name . ' — ' . $data['ref'],
                'journal_type' => 'contra',
                'source_type' => 'contra',
                'source_ref' => $data['ref'],
                'posted_by' => optional(auth()->user())->name,
                'status' => 'posted',
                'branch' => null,
            ]);

            JournalLine::create([
                'journal_id' => $journal->id,
                'line_number' => 1,
                'account_id' => (int) $toAcct->id,
                'description' => 'Contra in — ' . ($data['notes'] ?: $data['ref']),
                'debit' => $amount,
                'credit' => 0,
            ]);

            JournalLine::create([
                'journal_id' => $journal->id,
                'line_number' => 2,
                'account_id' => (int) $fromAcct->id,
                'description' => 'Contra out — ' . ($data['notes'] ?: $data['ref']),
                'debit' => 0,
                'credit' => $amount,
            ]);

            Account::whereKey($toAcct->id)->update([
                'balance' => DB::raw('balance + ' . $amount . ' - 0'),
            ]);

            Account::whereKey($fromAcct->id)->update([
                'balance' => DB::raw('balance + 0 - ' . $amount),
            ]);

            Voucher::create([
                'ref' => $data['ref'],
                'type' => 'contra',
                'posting_date' => $data['posting_date'],
                'description' => 'Contra — ' . $fromAcct->name . ' → ' . $toAcct->name,
                'total_amount' => $amount,
                'status' => 'posted',
                'journal_id' => $journal->id,
                'notes' => $data['notes'] ?: null,
                'posted_by' => optional(auth()->user())->name,
            ]);
        });

        return redirect()->route('admin.vouchers')->with('status', $data['ref'] . ' posted');
    }

    public function purchaseReturnCreate()
    {
        $suppliers = Supplier::query()->where('is_active', true)->orderBy('name')->get();
        $products = Product::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.vouchers.purchase_return', [
            'ref' => $this->nextRef('purchase_return'),
            'suppliers' => $suppliers,
            'products' => $products,
        ]);
    }

    public function purchaseReturnStore(Request $request)
    {
        $data = $request->validate([
            'posting_date' => ['required', 'date'],
            'ref' => ['required', 'string', 'max:50'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'original_grn' => ['nullable', 'string', 'max:100'],
            'reason' => ['required', 'string', 'max:50'],

            'lines' => ['required', 'array', 'min:1'],
            'lines.*.product_id' => ['required', 'exists:products,id'],
            'lines.*.qty' => ['required', 'numeric', 'min:0.01'],
        ]);

        $supplier = Supplier::findOrFail($data['supplier_id']);

        $productsById = Product::query()
            ->whereIn('id', collect($data['lines'])->pluck('product_id')->all())
            ->get()
            ->keyBy('id');

        $lines = collect($data['lines'])->map(function ($l) use ($productsById) {
            $prod = $productsById->get((int) $l['product_id']);
            $qty = (float) $l['qty'];
            $unit = $prod ? (float) $prod->cost_price : 0;
            $amount = $qty * $unit;

            return [
                'product_id' => (int) $l['product_id'],
                'qty' => $qty,
                'unit_cost' => $unit,
                'amount' => $amount,
                'description' => $prod ? $prod->name : 'Returned item',
            ];
        })->filter(fn ($l) => $l['amount'] > 0)->values();

        if ($lines->count() === 0) {
            return back()->withErrors(['lines' => 'Please add at least one valid line'])->withInput();
        }

        $total = (float) $lines->sum('amount');

        $ap = Account::where('code', '2010')->first();
        $inventory = Account::where('code', '1110')->first();
        if (!$ap || !$inventory) {
            return back()->withErrors(['supplier_id' => 'Required GL accounts not found (2010 AP, 1110 Inventory). Run seeders.'])->withInput();
        }

        DB::transaction(function () use ($data, $supplier, $lines, $total, $ap, $inventory, $productsById) {
            $journal = Journal::create([
                'ref' => 'JV-' . $data['ref'],
                'posting_date' => $data['posting_date'],
                'description' => 'Purchase Return — ' . $supplier->name . ' — ' . $data['ref'],
                'journal_type' => 'purchase_return',
                'source_type' => 'purchase_return',
                'source_ref' => $data['ref'],
                'posted_by' => optional(auth()->user())->name,
                'status' => 'posted',
                'branch' => null,
            ]);

            JournalLine::create([
                'journal_id' => $journal->id,
                'line_number' => 1,
                'account_id' => $ap->id,
                'description' => 'AP reduced — ' . $supplier->name,
                'debit' => $total,
                'credit' => 0,
            ]);

            JournalLine::create([
                'journal_id' => $journal->id,
                'line_number' => 2,
                'account_id' => $inventory->id,
                'description' => 'Inventory returned — ' . $data['ref'],
                'debit' => 0,
                'credit' => $total,
            ]);

            Account::whereKey($ap->id)->update([
                'balance' => DB::raw('balance + ' . $total . ' - 0'),
            ]);

            Account::whereKey($inventory->id)->update([
                'balance' => DB::raw('balance + 0 - ' . $total),
            ]);

            $notes = $data['reason'] . (!empty($data['original_grn']) ? ' · GRN: ' . $data['original_grn'] : '');

            $voucher = Voucher::create([
                'ref' => $data['ref'],
                'type' => 'purchase_return',
                'posting_date' => $data['posting_date'],
                'description' => 'Purchase Return — ' . $supplier->name,
                'total_amount' => $total,
                'status' => 'posted',
                'supplier_id' => $supplier->id,
                'journal_id' => $journal->id,
                'notes' => $notes,
                'posted_by' => optional(auth()->user())->name,
            ]);

            foreach ($lines as $idx => $l) {
                VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'line_number' => $idx + 1,
                    'product_id' => $l['product_id'],
                    'description' => $l['description'],
                    'qty' => $l['qty'],
                    'unit_cost' => $l['unit_cost'],
                    'subtotal' => $l['amount'],
                    'total' => $l['amount'],
                ]);

                $prod = $productsById->get($l['product_id']);
                if ($prod) {
                    $newQty = max(0, (float) $prod->qty_on_hand - (float) $l['qty']);
                    Product::whereKey($prod->id)->update(['qty_on_hand' => $newQty]);
                }
            }

            $supplier->balance_tzs = (float) $supplier->balance_tzs - $total;
            $supplier->save();

            VendorLedgerEntry::create([
                'supplier_id' => $supplier->id,
                'posting_date' => $data['posting_date'],
                'document_type' => 'credit_note',
                'document_ref' => $data['ref'],
                'description' => 'Purchase Return — ' . $supplier->name,
                'amount_tzs' => -$total,
                'remaining_amount' => 0,
                'is_open' => false,
                'due_date' => null,
                'journal_id' => (string) $journal->id,
            ]);
        });

        return redirect()->route('admin.vouchers')->with('status', $data['ref'] . ' posted');
    }

    public function bankTransferCreate()
    {
        $accounts = Account::query()
            ->where('is_active', true)
            ->where('type', 'asset')
            ->where('category', 'Cash & Bank')
            ->orderBy('code')
            ->get();

        return view('admin.vouchers.bank_transfer', [
            'ref' => $this->nextRef('bank_transfer'),
            'accounts' => $accounts,
        ]);
    }

    public function bankTransferStore(Request $request)
    {
        $data = $request->validate([
            'posting_date' => ['required', 'date'],
            'ref' => ['required', 'string', 'max:50'],
            'from_account_id' => ['required', 'exists:accounts,id'],
            'to_account_id' => ['required', 'exists:accounts,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'narration' => ['nullable', 'string', 'max:255'],
        ]);

        if ((string) $data['from_account_id'] === (string) $data['to_account_id']) {
            return back()->withErrors(['to_account_id' => 'From and To accounts cannot be the same'])->withInput();
        }

        $amount = (float) $data['amount'];
        $fromAcct = Account::findOrFail($data['from_account_id']);
        $toAcct = Account::findOrFail($data['to_account_id']);

        DB::transaction(function () use ($data, $amount, $fromAcct, $toAcct) {
            $journal = Journal::create([
                'ref' => 'JV-' . $data['ref'],
                'posting_date' => $data['posting_date'],
                'description' => 'Bank Transfer — ' . $fromAcct->code . ' to ' . $toAcct->code . ' — ' . $data['ref'],
                'journal_type' => 'bank_transfer',
                'source_type' => 'bank_transfer',
                'source_ref' => $data['ref'],
                'posted_by' => optional(auth()->user())->name,
                'status' => 'posted',
                'branch' => null,
            ]);

            JournalLine::create([
                'journal_id' => $journal->id,
                'line_number' => 1,
                'account_id' => (int) $toAcct->id,
                'description' => 'Transfer in — ' . ($data['narration'] ?: $data['ref']),
                'debit' => $amount,
                'credit' => 0,
            ]);

            JournalLine::create([
                'journal_id' => $journal->id,
                'line_number' => 2,
                'account_id' => (int) $fromAcct->id,
                'description' => 'Transfer out — ' . ($data['narration'] ?: $data['ref']),
                'debit' => 0,
                'credit' => $amount,
            ]);

            Account::whereKey($toAcct->id)->update([
                'balance' => DB::raw('balance + ' . $amount),
            ]);

            Account::whereKey($fromAcct->id)->update([
                'balance' => DB::raw('balance - ' . $amount),
            ]);

            Voucher::create([
                'ref' => $data['ref'],
                'type' => 'bank_transfer',
                'posting_date' => $data['posting_date'],
                'description' => 'Bank Transfer — ' . $data['ref'],
                'total_amount' => $amount,
                'status' => 'posted',
                'journal_id' => $journal->id,
                'notes' => $data['narration'] ?: null,
                'posted_by' => optional(auth()->user())->name,
            ]);
        });

        return redirect()->route('admin.vouchers')->with('status', $data['ref'] . ' posted');
    }

    public function cashReceiptCreate()
    {
        $accounts = Account::query()->where('is_active', true)->orderBy('code')->get();
        $customers = Customer::query()->where('is_active', true)->orderBy('name')->get();

        $cashAccounts = $accounts->filter(fn ($a) => $a->category === 'Cash & Bank')->values();
        $creditAccounts = $accounts->filter(fn ($a) => in_array($a->code, ['4010', '1050', '1200'], true))->values();

        return view('admin.vouchers.cash_receipt', [
            'ref' => $this->nextRef('cash_receipt'),
            'accounts' => $accounts,
            'customers' => $customers,
            'cashAccounts' => $cashAccounts,
            'creditAccounts' => $creditAccounts,
        ]);
    }

    public function cashReceiptStore(Request $request)
    {
        $data = $request->validate([
            'posting_date' => ['required', 'date'],
            'ref' => ['required', 'string', 'max:50'],
            'received_from' => ['required', 'string', 'max:255'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'cash_account_id' => ['required', 'exists:accounts,id'],
            'credit_account_id' => ['required', 'exists:accounts,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'max:30'],
            'narration' => ['nullable', 'string', 'max:255'],
        ]);

        $amount = (float) $data['amount'];
        $cashAcct = Account::findOrFail($data['cash_account_id']);
        $creditAcct = Account::findOrFail($data['credit_account_id']);

        DB::transaction(function () use ($data, $amount, $cashAcct, $creditAcct) {
            $journal = Journal::create([
                'ref' => 'JV-' . $data['ref'],
                'posting_date' => $data['posting_date'],
                'description' => 'Cash Receipt — ' . $data['received_from'] . ' — ' . $data['ref'],
                'journal_type' => 'cash_receipt',
                'source_type' => 'cash_receipt',
                'source_ref' => $data['ref'],
                'posted_by' => optional(auth()->user())->name,
                'status' => 'posted',
                'branch' => null,
            ]);

            JournalLine::create([
                'journal_id' => $journal->id,
                'line_number' => 1,
                'account_id' => (int) $cashAcct->id,
                'description' => 'Received from ' . $data['received_from'],
                'debit' => $amount,
                'credit' => 0,
            ]);

            JournalLine::create([
                'journal_id' => $journal->id,
                'line_number' => 2,
                'account_id' => (int) $creditAcct->id,
                'description' => 'Income — ' . ($data['narration'] ?: $data['received_from']),
                'debit' => 0,
                'credit' => $amount,
            ]);

            Account::whereKey($cashAcct->id)->update([
                'balance' => DB::raw('balance + ' . $amount . ' - 0'),
            ]);

            Account::whereKey($creditAcct->id)->update([
                'balance' => DB::raw('balance + 0 - ' . $amount),
            ]);

            $voucher = Voucher::create([
                'ref' => $data['ref'],
                'type' => 'cash_receipt',
                'posting_date' => $data['posting_date'],
                'description' => 'Cash Receipt — ' . $data['received_from'],
                'total_amount' => $amount,
                'status' => 'posted',
                'journal_id' => $journal->id,
                'payment_method' => $data['payment_method'],
                'notes' => $data['narration'] ?: null,
                'posted_by' => optional(auth()->user())->name,
                'customer_id' => $data['customer_id'] ?: null,
            ]);

            if (!empty($data['customer_id'])) {
                $customer = Customer::find($data['customer_id']);
                if ($customer) {
                    $customer->balance = (float) $customer->balance - $amount;
                    $customer->save();

                    CustomerLedgerEntry::create([
                        'customer_id' => $customer->id,
                        'posting_date' => $data['posting_date'],
                        'document_type' => 'payment',
                        'document_ref' => $data['ref'],
                        'description' => 'Cash Receipt — ' . $data['received_from'] . (!empty($data['narration']) ? ' — ' . $data['narration'] : ''),
                        'amount' => -$amount,
                        'remaining_amount' => 0,
                        'is_open' => false,
                        'due_date' => null,
                    ]);
                }
            }
        });

        return redirect()->route('admin.vouchers')->with('status', $data['ref'] . ' posted');
    }

    public function purchaseInvoiceCreate()
    {
        $suppliers = Supplier::query()->where('is_active', true)->orderBy('name')->get();
        $products = Product::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.vouchers.purchase_invoice', [
            'ref' => $this->nextRef('purchase_invoice'),
            'suppliers' => $suppliers,
            'products' => $products,
        ]);
    }

    public function purchaseInvoiceStore(Request $request)
    {
        $data = $request->validate([
            'posting_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'ref' => ['required', 'string', 'max:50'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'supplier_ref' => ['nullable', 'string', 'max:100'],
            'po_ref' => ['nullable', 'string', 'max:100'],
            'grn_ref' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:255'],

            'lines' => ['required', 'array', 'min:1'],
            'lines.*.product_id' => ['nullable', 'exists:products,id'],
            'lines.*.description' => ['nullable', 'string', 'max:255'],
            'lines.*.qty' => ['nullable', 'numeric', 'min:0'],
            'lines.*.unit_cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $supplier = Supplier::findOrFail($data['supplier_id']);

        $lines = collect($data['lines'] ?? [])->map(function ($l) {
            $qty = (float) ($l['qty'] ?? 0);
            $unit = (float) ($l['unit_cost'] ?? 0);
            $amount = $qty * $unit;

            return [
                'product_id' => $l['product_id'] ?? null,
                'description' => trim((string) ($l['description'] ?? '')),
                'qty' => $qty,
                'unit_cost' => $unit,
                'amount' => $amount,
            ];
        })->filter(function ($l) {
            return $l['amount'] > 0 && $l['description'] !== '';
        })->values();

        if ($lines->count() === 0) {
            return back()->withErrors(['lines' => 'Please add at least one valid line'])->withInput();
        }

        $total = (float) $lines->sum('amount');
        if ($total <= 0) {
            return back()->withErrors(['lines' => 'Total amount must be greater than zero'])->withInput();
        }

        $grnInterim = Account::where('code', '1121')->first();
        $ap = Account::where('code', '2010')->first();

        if (!$grnInterim || !$ap) {
            return back()->withErrors(['exp_account_id' => 'Accounts 1121 or 2010 not found. Run seeders.'])->withInput();
        }

        DB::transaction(function () use ($data, $supplier, $lines, $total, $grnInterim, $ap) {
            $journal = Journal::create([
                'ref' => 'JV-' . $data['ref'],
                'posting_date' => $data['posting_date'],
                'description' => 'Purchase Invoice — ' . $supplier->name . ' — ' . $data['ref'],
                'journal_type' => 'purchase_invoice',
                'source_type' => 'purchase_invoice',
                'source_ref' => $data['ref'],
                'posted_by' => optional(auth()->user())->name,
                'status' => 'posted',
                'branch' => null,
            ]);

            JournalLine::create([
                'journal_id' => $journal->id,
                'line_number' => 1,
                'account_id' => $grnInterim->id,
                'description' => 'GRN Interim cleared — ' . (!empty($data['grn_ref']) ? $data['grn_ref'] : $data['ref']),
                'debit' => $total,
                'credit' => 0,
            ]);

            JournalLine::create([
                'journal_id' => $journal->id,
                'line_number' => 2,
                'account_id' => $ap->id,
                'description' => 'AP — ' . $supplier->name . ' — ' . $data['ref'],
                'debit' => 0,
                'credit' => $total,
            ]);

            Account::whereKey($grnInterim->id)->update([
                'balance' => DB::raw('balance + ' . $total . ' - 0'),
            ]);

            Account::whereKey($ap->id)->update([
                'balance' => DB::raw('balance + 0 - ' . $total),
            ]);

            $voucher = Voucher::create([
                'ref' => $data['ref'],
                'type' => 'purchase_invoice',
                'posting_date' => $data['posting_date'],
                'due_date' => $data['due_date'] ?? null,
                'description' => 'Purchase Invoice — ' . $supplier->name,
                'total_amount' => $total,
                'status' => 'posted',
                'supplier_id' => $supplier->id,
                'journal_id' => $journal->id,
                'notes' => $data['notes'] ?? null,
                'posted_by' => optional(auth()->user())->name,
            ]);

            foreach ($lines as $idx => $l) {
                VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'line_number' => $idx + 1,
                    'product_id' => $l['product_id'],
                    'description' => $l['description'],
                    'qty' => $l['qty'],
                    'unit_cost' => $l['unit_cost'],
                    'subtotal' => $l['amount'],
                    'total' => $l['amount'],
                ]);
            }

            $supplier->balance_tzs = (float) $supplier->balance_tzs + $total;
            $supplier->save();

            VendorLedgerEntry::create([
                'supplier_id' => $supplier->id,
                'posting_date' => $data['posting_date'],
                'document_type' => 'invoice',
                'document_ref' => $data['ref'],
                'description' => 'Purchase Invoice — ' . $supplier->name,
                'amount_tzs' => $total,
                'remaining_amount' => $total,
                'is_open' => true,
                'due_date' => $data['due_date'] ?? null,
                'journal_id' => (string) $journal->id,
            ]);
        });

        return redirect()->route('admin.vouchers')->with('status', $data['ref'] . ' posted');
    }

    public function cashPaymentCreate()
    {
        $accounts = Account::query()->where('is_active', true)->orderBy('code')->get();
        $suppliers = Supplier::query()->where('is_active', true)->orderBy('name')->get();

        $cashAccounts = $accounts->filter(fn ($a) => $a->category === 'Cash & Bank')->values();
        $expenseAccounts = $accounts->filter(fn ($a) => in_array($a->type, ['liability', 'expense', 'cogs'], true))->values();

        return view('admin.vouchers.cash_payment', [
            'ref' => $this->nextRef('cash_payment'),
            'accounts' => $accounts,
            'suppliers' => $suppliers,
            'cashAccounts' => $cashAccounts,
            'expenseAccounts' => $expenseAccounts,
        ]);
    }

    public function cashPaymentStore(Request $request)
    {
        $data = $request->validate([
            'posting_date' => ['required', 'date'],
            'ref' => ['required', 'string', 'max:50'],
            'pay_to' => ['required', 'string', 'max:255'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'exp_account_id' => ['required', 'exists:accounts,id'],
            'cash_account_id' => ['required', 'exists:accounts,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'narration' => ['nullable', 'string', 'max:255'],
            'cheque_no' => ['nullable', 'string', 'max:100'],
            'branch' => ['required', 'string', 'max:100'],
        ]);

        $amount = (float) $data['amount'];

        $cashAcct = Account::findOrFail($data['cash_account_id']);
        $expAcct = Account::findOrFail($data['exp_account_id']);

        DB::transaction(function () use ($data, $amount, $cashAcct, $expAcct) {
            $journal = Journal::create([
                'ref' => 'JV-' . $data['ref'],
                'posting_date' => $data['posting_date'],
                'description' => 'Cash Payment — ' . $data['pay_to'] . ' — ' . $data['ref'],
                'journal_type' => 'cash_payment',
                'source_type' => 'cash_payment',
                'source_ref' => $data['ref'],
                'posted_by' => optional(auth()->user())->name,
                'status' => 'posted',
                'branch' => $data['branch'],
            ]);

            JournalLine::create([
                'journal_id' => $journal->id,
                'line_number' => 1,
                'account_id' => (int) $data['exp_account_id'],
                'description' => $data['narration'] ?: $data['pay_to'],
                'debit' => $amount,
                'credit' => 0,
            ]);

            JournalLine::create([
                'journal_id' => $journal->id,
                'line_number' => 2,
                'account_id' => (int) $data['cash_account_id'],
                'description' => 'Cash paid — ' . $data['pay_to'],
                'debit' => 0,
                'credit' => $amount,
            ]);

            Account::whereKey($expAcct->id)->update([
                'balance' => DB::raw('balance + ' . $amount . ' - 0'),
            ]);

            Account::whereKey($cashAcct->id)->update([
                'balance' => DB::raw('balance + 0 - ' . $amount),
            ]);

            Voucher::create([
                'ref' => $data['ref'],
                'type' => 'cash_payment',
                'posting_date' => $data['posting_date'],
                'description' => 'Cash Payment — ' . $data['pay_to'],
                'total_amount' => $amount,
                'status' => 'posted',
                'branch' => $data['branch'],
                'supplier_id' => $data['supplier_id'] ?: null,
                'journal_id' => $journal->id,
                'payment_method' => 'cash',
                'notes' => $data['narration'] ?: null,
                'posted_by' => optional(auth()->user())->name,
            ]);

            if (!empty($data['supplier_id'])) {
                $supplier = Supplier::find($data['supplier_id']);
                if ($supplier) {
                    $supplier->balance_tzs = (float) $supplier->balance_tzs - $amount;
                    $supplier->save();
                }

                VendorLedgerEntry::create([
                    'supplier_id' => (int) $data['supplier_id'],
                    'posting_date' => $data['posting_date'],
                    'document_type' => 'payment',
                    'document_ref' => $data['ref'],
                    'description' => 'Cash Payment — ' . $data['pay_to'] . (!empty($data['narration']) ? ' — ' . $data['narration'] : ''),
                    'amount_tzs' => -$amount,
                    'remaining_amount' => 0,
                    'is_open' => false,
                    'journal_id' => (string) $journal->id,
                ]);
            }
        });

        return redirect()->route('admin.vouchers')->with('status', $data['ref'] . ' posted');
    }

    private function nextRef(string $type): string
    {
        $prefix = match ($type) {
            'cash_payment' => 'CP-'
            ,'purchase_invoice' => 'PI-'
            ,'cash_receipt' => 'CR-'
            ,'bank_transfer' => 'BT-'
            ,'purchase_return' => 'PR-'
            ,'contra' => 'CE-'
            ,'credit_note' => 'CN-'
            ,'sales_invoice' => 'SI-'
            ,default => 'VCH-',
        };

        $last = Voucher::query()->where('type', $type)->orderByDesc('id')->value('ref');
        $lastNum = 0;
        if ($last && str_starts_with($last, $prefix)) {
            $lastNum = (int) preg_replace('/\D+/', '', $last);
        }

        return $prefix . str_pad((string) ($lastNum + 1), 6, '0', STR_PAD_LEFT);
    }
}
