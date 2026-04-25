<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.erp.inventory_products', [
            'title' => 'Products Management',
            'products' => $products,
        ]);
    }

    public function create()
    {
        return view('admin.erp.inventory_products_create', [
            'title' => 'Add Product',
        ]);
    }

    public function edit(Product $product)
    {
        return view('admin.erp.inventory_products_edit', [
            'title' => 'Edit Product',
            'product' => $product,
        ]);
    }

    public function import()
    {
        return view('admin.erp.inventory_products_import', [
            'title' => 'Bulk Import Products',
        ]);
    }

    public function downloadTemplate()
    {
        $lines = [
            'name,sku,barcode,category,cost_price,selling_price,qty_on_hand,image_url',
            'Baby Diapers (Pack of 50),DIAPERS-50,,Supplies,18000,25000,20,',
            'Vitamin C,VITC-001,,Health,5000,8500,12,',
        ];

        $csv = implode("\n", $lines) . "\n";

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="products_import_template.csv"',
        ]);
    }

    public function importPreview(Request $request)
    {
        $request->validate([
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

        while (($data = fgetcsv($handle)) !== false) {
            $line++;
            if (count(array_filter($data, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }

            $row = [];
            foreach ($header as $idx => $key) {
                $row[$key] = $data[$idx] ?? null;
            }

            $name = trim((string) ($row['name'] ?? ''));
            $sku = trim((string) ($row['sku'] ?? ''));
            $barcode = trim((string) ($row['barcode'] ?? ''));
            $category = trim((string) ($row['category'] ?? ''));
            $costPrice = $row['cost_price'] ?? $row['cost'] ?? null;
            $sellingPrice = $row['selling_price'] ?? $row['price'] ?? null;
            $qtyOnHand = $row['qty_on_hand'] ?? $row['qty'] ?? $row['stock'] ?? null;
            $imageUrl = trim((string) ($row['image_url'] ?? $row['image'] ?? ''));

            $errors = [];

            if ($name === '') $errors[] = 'Name is required';

            if ($sku === '') {
                $sku = $this->generateUniqueSkuFromName($name);
            } else {
                $sku = $this->generateUniqueSku($sku);
            }

            if ($barcode === '') {
                $barcode = $this->generateBarcodeFromSku($sku);
            }

            $costPrice = is_numeric($costPrice) ? (float) $costPrice : null;
            $sellingPrice = is_numeric($sellingPrice) ? (float) $sellingPrice : null;
            $qtyOnHand = is_numeric($qtyOnHand) ? (float) $qtyOnHand : null;

            if ($costPrice === null || $costPrice < 0) $errors[] = 'Cost price must be numeric and >= 0';
            if ($sellingPrice === null || $sellingPrice < 0) $errors[] = 'Selling price must be numeric and >= 0';
            if ($qtyOnHand === null || $qtyOnHand < 0) $errors[] = 'Qty on hand must be numeric and >= 0';
            if ($imageUrl !== '' && !filter_var($imageUrl, FILTER_VALIDATE_URL)) $errors[] = 'Image URL is invalid';

            $payload = [
                'name' => $name,
                'sku' => $sku,
                'barcode' => $barcode,
                'category' => $category !== '' ? $category : null,
                'cost_price' => $costPrice,
                'selling_price' => $sellingPrice,
                'qty_on_hand' => $qtyOnHand,
                'image_url' => $imageUrl !== '' ? $imageUrl : null,
                'is_active' => true,
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
        session(['products_import_rows' => $validRows]);

        return view('admin.erp.inventory_products_import_preview', [
            'title' => 'Import Preview',
            'rows' => $rows,
            'validCount' => $validCount,
            'invalidCount' => $invalidCount,
        ]);
    }

    public function importConfirm()
    {
        $rows = session('products_import_rows', []);
        if (empty($rows)) {
            return redirect()->route('admin.inventory.products.import')->withErrors(['file' => 'No preview data found. Please upload again.']);
        }

        $created = 0;

        DB::transaction(function () use ($rows, &$created) {
            foreach ($rows as $r) {
                $payload = $r['payload'];

                // Safety: ensure unique SKU + barcode at import time
                $payload['sku'] = $this->generateUniqueSku($payload['sku']);
                $payload['barcode'] = $this->generateUniqueBarcode($payload['barcode']);

                Product::create($payload);
                $created++;
            }
        });

        session()->forget('products_import_rows');
        return redirect()->route('admin.inventory.products')->with('status', "Imported {$created} products successfully");
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => ['required', 'file', 'image', 'max:5120'],
        ]);

        $path = $request->file('image')->store('products', 'public');
        $url = Storage::disk('public')->url($path);

        return response()->json([
            'success' => true,
            'url' => $url,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'barcode' => ['nullable', 'string', 'max:100', 'unique:products,barcode'],
            'category' => ['nullable', 'string', 'max:100'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'qty_on_hand' => ['required', 'numeric', 'min:0'],
            'image_url' => ['nullable', 'url'],
            'image' => ['nullable', 'file', 'image', 'max:5120'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->has('is_active');

        if (empty($data['barcode'])) {
            $data['barcode'] = $this->generateBarcodeFromSku($data['sku']);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image_url'] = Storage::disk('public')->url($path);
        }

        Product::create($data);

        return redirect()->route('admin.inventory.products')->with('status', 'Product created successfully');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku,' . $product->id],
            'barcode' => ['nullable', 'string', 'max:100', 'unique:products,barcode,' . $product->id],
            'category' => ['nullable', 'string', 'max:100'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'qty_on_hand' => ['required', 'numeric', 'min:0'],
            'image_url' => ['nullable', 'url'],
            'image' => ['nullable', 'file', 'image', 'max:5120'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->has('is_active');

        if (empty($data['barcode'])) {
            $data['barcode'] = $this->generateBarcodeFromSku($data['sku'], $product->id);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image_url'] = Storage::disk('public')->url($path);
        }

        $product->update($data);

        return redirect()->route('admin.inventory.products')->with('status', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('status', 'Product deleted successfully');
    }

    private function generateBarcodeFromSku(string $sku, ?int $ignoreId = null): string
    {
        $base = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $sku) ?? '');
        if ($base === '') {
            $base = strtoupper(bin2hex(random_bytes(4)));
        }

        $base = substr($base, 0, 90);
        $candidate = $base;
        $i = 1;

        while (
            Product::query()
                ->where('barcode', $candidate)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $suffix = '-' . $i;
            $candidate = substr($base, 0, 100 - strlen($suffix)) . $suffix;
            $i++;
        }

        return $candidate;
    }

    private function generateUniqueSku(string $sku, ?int $ignoreId = null): string
    {
        $base = strtoupper(preg_replace('/[^A-Za-z0-9\-]/', '', $sku) ?? '');
        if ($base === '') {
            $base = 'SKU-' . strtoupper(bin2hex(random_bytes(3)));
        }

        $base = substr($base, 0, 90);
        $candidate = $base;
        $i = 1;

        while (
            Product::query()
                ->where('sku', $candidate)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
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
        $sku = $slug . '-' . strtoupper(bin2hex(random_bytes(3)));
        return $this->generateUniqueSku($sku);
    }

    private function generateUniqueBarcode(string $barcode, ?int $ignoreId = null): string
    {
        $base = strtoupper(preg_replace('/[^A-Za-z0-9\-]/', '', $barcode) ?? '');
        if ($base === '') {
            $base = strtoupper(bin2hex(random_bytes(4)));
        }

        $base = substr($base, 0, 90);
        $candidate = $base;
        $i = 1;

        while (
            Product::query()
                ->where('barcode', $candidate)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $suffix = '-' . $i;
            $candidate = substr($base, 0, 100 - strlen($suffix)) . $suffix;
            $i++;
        }

        return $candidate;
    }
}
