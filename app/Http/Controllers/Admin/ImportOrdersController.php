<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportOrder;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\VendorLedgerEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportOrdersController extends Controller
{
    public function index(Request $request)
    {
        $query = ImportOrder::query()->with(['supplier'])->orderByDesc('posting_date')->orderByDesc('id');

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('ref', 'like', "%{$search}%")
                    ->orWhere('supplier_name', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.erp.import_orders.index', [
            'title' => 'Import Orders',
            'orders' => $orders,
        ]);
    }

    public function create()
    {
        $suppliers = Supplier::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.erp.import_orders.upload', [
            'title' => 'Import Order',
            'suppliers' => $suppliers,
            'defaultDate' => now()->toDateString(),
            'nextRef' => $this->nextRef('IMP-'),
        ]);
    }

    public function downloadTemplate()
    {
        $lines = [
            'product_name,sku,barcode,qty,unit_cost,cost_price,selling_price,category,image_url',
            'Baby Diapers (Pack of 50),DIAPERS-50,,20,18000,18000,25000,Supplies,',
            'Vitamin C,VITC-001,,12,5000,5000,8500,Health,',
        ];

        $csv = implode("\n", $lines) . "\n";

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="import_order_template.csv"',
        ]);
    }

    public function preview(Request $request)
    {
        $data = $request->validate([
            'ref' => ['required', 'string', 'max:50'],
            'posting_date' => ['required', 'date'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'import_type' => ['required', 'string', 'in:product,opening_stock,adjustment'],
            'notes' => ['nullable', 'string'],
            'file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');

        if (!$handle) {
            return back()->withErrors(['file' => 'Unable to read CSV file']);
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return back()->withErrors(['file' => 'CSV file is empty']);
        }

        $header = array_map(fn ($h) => strtolower(trim((string) $h)), $header);

        $rows = [];
        $validCount = 0;
        $invalidCount = 0;
        $line = 1;

        while (($rowData = fgetcsv($handle)) !== false) {
            $line++;
            if (count(array_filter($rowData, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }

            $row = [];
            foreach ($header as $idx => $key) {
                $row[$key] = $rowData[$idx] ?? null;
            }

            $productName = trim((string) ($row['product_name'] ?? $row['name'] ?? ''));
            $sku = trim((string) ($row['sku'] ?? ''));
            $barcode = trim((string) ($row['barcode'] ?? ''));
            $qty = $row['qty'] ?? $row['quantity'] ?? null;
            $unitCost = $row['unit_cost'] ?? $row['cost'] ?? null;

            $costPrice = $row['cost_price'] ?? $unitCost;
            $sellingPrice = $row['selling_price'] ?? $row['price'] ?? null;
            $category = trim((string) ($row['category'] ?? ''));
            $imageUrl = trim((string) ($row['image_url'] ?? $row['image'] ?? ''));

            $errors = [];

            if ($productName === '') $errors[] = 'Product name is required';

            $qty = is_numeric($qty) ? (float) $qty : null;
            $unitCost = is_numeric($unitCost) ? (float) $unitCost : null;
            $costPrice = is_numeric($costPrice) ? (float) $costPrice : null;
            $sellingPrice = is_numeric($sellingPrice) ? (float) $sellingPrice : null;

            if ($qty === null || $qty <= 0) $errors[] = 'Qty must be numeric and > 0';
            if ($unitCost === null || $unitCost < 0) $errors[] = 'Unit cost must be numeric and >= 0';

            if ($costPrice === null || $costPrice < 0) $costPrice = $unitCost ?? 0;
            if ($sellingPrice === null || $sellingPrice < 0) $sellingPrice = max(0, ($unitCost ?? 0) * 1.2);
            if ($imageUrl !== '' && !filter_var($imageUrl, FILTER_VALIDATE_URL)) $errors[] = 'Image URL is invalid';

            if ($sku === '') {
                $sku = $this->generateUniqueSkuFromName($productName);
            } else {
                $sku = $this->generateUniqueSku($sku);
            }

            if ($barcode === '') {
                $barcode = $this->generateBarcodeFromSku($sku);
            } else {
                $barcode = $this->generateUniqueBarcode($barcode);
            }

            $lineTotal = ($qty ?? 0) * ($unitCost ?? 0);

            $payload = [
                'product_name' => $productName,
                'sku' => $sku,
                'barcode' => $barcode,
                'qty' => $qty,
                'unit_cost' => $unitCost,
                'line_total' => $lineTotal,
                'category' => $category !== '' ? $category : null,
                'image_url' => $imageUrl !== '' ? $imageUrl : null,
                'cost_price' => $costPrice,
                'selling_price' => $sellingPrice,
            ];

            if (empty($errors)) {
                $validCount++;
            } else {
                $invalidCount++;
            }

            $rows[] = [
                'line' => $line,
                'payload' => $payload,
                'errors' => $errors,
            ];
        }

        fclose($handle);

        $validRows = array_values(array_filter($rows, fn ($r) => empty($r['errors'])));

            session([
                'import_order_meta' => [
                    'ref' => $data['ref'],
                    'posting_date' => $data['posting_date'],
                    'supplier_id' => $data['supplier_id'] ?? null,
                    'supplier_name' => $data['supplier_name'] ?? null,
                    'import_type' => $data['import_type'],
                    'notes' => $data['notes'] ?? null,
                    'source_file_name' => $request->file('file')->getClientOriginalName(),
                ],
                'import_order_rows' => $validRows,
            ]);

        $totalCost = collect($validRows)->sum(fn ($r) => (float) $r['payload']['line_total']);

        return view('admin.erp.import_orders.preview', [
            'title' => 'Import Preview',
            'rows' => $rows,
            'validCount' => $validCount,
            'invalidCount' => $invalidCount,
            'totalCost' => $totalCost,
            'meta' => session('import_order_meta'),
        ]);
    }

    public function confirm()
    {
        $meta = session('import_order_meta');
        $rows = session('import_order_rows', []);

        if (empty($meta) || empty($meta['ref'])) {
            return redirect()->route('admin.imports.import-order.create')->withErrors(['file' => 'No preview found. Please upload again.']);
        }

        if (empty($rows)) {
            return redirect()->route('admin.imports.import-order.create')->withErrors(['file' => 'No valid rows to import.']);
        }

        $createdOrder = null;

        DB::transaction(function () use ($meta, $rows, &$createdOrder) {
            $supplier = null;

            if (!empty($meta['supplier_id'])) {
                $supplier = Supplier::find($meta['supplier_id']);
            }

            if (!$supplier && !empty($meta['supplier_name'])) {
                $supplier = Supplier::firstOrCreate(
                    ['name' => $meta['supplier_name']],
                    ['code' => $this->nextSupplierCode(), 'is_active' => true]
                );
            }

            $totalCost = 0.0;

            $order = ImportOrder::create([
                'ref' => $meta['ref'],
                'posting_date' => $meta['posting_date'],
                'supplier_id' => $supplier?->id,
                'supplier_name' => $supplier?->name ?: ($meta['supplier_name'] ?? null),
                'source_file_name' => $meta['source_file_name'] ?? null,
                'notes' => $meta['notes'] ?? null,
                'created_by' => auth()->id(),
                'total_lines' => count($rows),
                'total_cost' => 0,
                'status' => 'completed',
                'import_type' => $meta['import_type'] ?? 'product',
            ]);

            foreach ($rows as $r) {
                $p = $r['payload'];

                $product = Product::query()
                    ->where('sku', $p['sku'])
                    ->orWhere('barcode', $p['barcode'])
                    ->first();

                if (!$product) {
                    $product = Product::create([
                        'name' => $p['product_name'],
                        'sku' => $p['sku'],
                        'barcode' => $p['barcode'],
                        'category' => $p['category'],
                        'cost_price' => $p['cost_price'],
                        'selling_price' => $p['selling_price'],
                        'qty_on_hand' => 0,
                        'image_url' => $p['image_url'],
                        'is_active' => true,
                    ]);
                } else {
                    $product->update([
                        'name' => $p['product_name'] ?: $product->name,
                        'category' => $p['category'] ?: $product->category,
                        'image_url' => $p['image_url'] ?: $product->image_url,
                        'cost_price' => (float) $p['cost_price'],
                        'selling_price' => (float) $p['selling_price'],
                    ]);
                }

                $product->increment('qty_on_hand', (float) $p['qty']);

                $lineTotal = (float) $p['line_total'];
                $totalCost += $lineTotal;

                $order->lines()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'barcode' => $product->barcode,
                    'qty' => (float) $p['qty'],
                    'unit_cost' => (float) $p['unit_cost'],
                    'line_total' => $lineTotal,
                ]);
            }

            $order->update([
                'total_cost' => $totalCost,
            ]);

            if ($supplier) {
                $supplier->balance_tzs = (float) $supplier->balance_tzs + $totalCost;
                $supplier->save();

                VendorLedgerEntry::create([
                    'supplier_id' => $supplier->id,
                    'posting_date' => $meta['posting_date'],
                    'document_type' => 'import_order',
                    'document_ref' => $meta['ref'],
                    'description' => 'Import Order — ' . $meta['ref'],
                    'amount_tzs' => $totalCost,
                    'amount' => $totalCost,
                    'remaining_amount' => $totalCost,
                    'is_open' => true,
                    'due_date' => null,
                    'journal_id' => null,
                    'import_order_ref' => $meta['ref'],
                ]);
            }

            $createdOrder = $order;
        });

        session()->forget(['import_order_meta', 'import_order_rows']);

        return redirect()->route('admin.imports.import-order.index')->with('status', 'Import order posted: ' . ($createdOrder?->ref ?? ''));
    }

    public function show(ImportOrder $importOrder)
    {
        $importOrder->load(['supplier', 'lines.product', 'creator']);

        return view('admin.erp.import_orders.show', [
            'title' => 'Import Order ' . $importOrder->ref,
            'order' => $importOrder,
        ]);
    }

    private function nextRef(string $prefix): string
    {
        $last = ImportOrder::query()
            ->where('ref', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('ref');

        $n = 1;
        if (is_string($last) && preg_match('/(\d+)$/', $last, $m)) {
            $n = ((int) $m[1]) + 1;
        }

        return $prefix . str_pad((string) $n, 5, '0', STR_PAD_LEFT);
    }

    private function nextSupplierCode(): string
    {
        $last = Supplier::orderByDesc('code')->value('code');
        $lastNum = 0;
        if ($last) {
            $lastNum = (int) preg_replace('/\D+/', '', $last);
        }

        return 'SUP-' . str_pad((string) ($lastNum + 1), 3, '0', STR_PAD_LEFT);
    }

    private function generateBarcodeFromSku(string $sku): string
    {
        $base = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $sku) ?? '');
        $base = $base !== '' ? substr($base, 0, 90) : strtoupper(bin2hex(random_bytes(4)));
        return $this->generateUniqueBarcode($base);
    }

    private function generateUniqueSku(string $sku): string
    {
        $base = strtoupper(preg_replace('/[^A-Za-z0-9\-]/', '', $sku) ?? '');
        $base = $base !== '' ? substr($base, 0, 90) : 'SKU-' . strtoupper(bin2hex(random_bytes(3)));

        $candidate = $base;
        $i = 1;
        while (Product::query()->where('sku', $candidate)->exists()) {
            $suffix = '-' . $i;
            $candidate = substr($base, 0, 100 - strlen($suffix)) . $suffix;
            $i++;
        }
        return $candidate;
    }

    private function generateUniqueSkuFromName(string $name): string
    {
        $slug = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $name) ?? '');
        $slug = $slug !== '' ? substr($slug, 0, 12) : 'SKU';
        return $this->generateUniqueSku($slug . '-' . strtoupper(bin2hex(random_bytes(3))));
    }

    private function generateUniqueBarcode(string $barcode): string
    {
        $base = strtoupper(preg_replace('/[^A-Za-z0-9\-]/', '', $barcode) ?? '');
        $base = $base !== '' ? substr($base, 0, 90) : strtoupper(bin2hex(random_bytes(4)));

        $candidate = $base;
        $i = 1;
        while (Product::query()->where('barcode', $candidate)->exists()) {
            $suffix = '-' . $i;
            $candidate = substr($base, 0, 100 - strlen($suffix)) . $suffix;
            $i++;
        }
        return $candidate;
    }
}
