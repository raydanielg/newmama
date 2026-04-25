<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PosBundle;
use App\Models\Product;
use Illuminate\Http\Request;

class PosBundlesController extends Controller
{
    public function index(Request $request)
    {
        $query = PosBundle::query()->withCount('products');

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $bundles = $query->orderBy('name')->paginate(15)->withQueryString();
        $products = Product::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.pos.bundles', [
            'title' => 'POS Bundles',
            'bundles' => $bundles,
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sku' => ['required', 'string', 'max:100', 'unique:pos_bundles,sku'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image_url' => ['nullable', 'url'],
            'is_active' => ['nullable', 'boolean'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => ['integer', 'exists:products,id'],
            'product_qtys' => ['nullable', 'array'],
            'product_qtys.*' => ['nullable', 'numeric', 'min:0.01'],
        ]);

        $data['is_active'] = $request->has('is_active');

        $bundle = PosBundle::create($data);
        $this->syncBundleProducts($bundle, $request);

        return back()->with('status', 'Bundle created successfully');
    }

    public function update(Request $request, PosBundle $bundle)
    {
        $data = $request->validate([
            'sku' => ['required', 'string', 'max:100', 'unique:pos_bundles,sku,' . $bundle->id],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image_url' => ['nullable', 'url'],
            'is_active' => ['nullable', 'boolean'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => ['integer', 'exists:products,id'],
            'product_qtys' => ['nullable', 'array'],
            'product_qtys.*' => ['nullable', 'numeric', 'min:0.01'],
        ]);

        $data['is_active'] = $request->has('is_active');
        $bundle->update($data);

        $this->syncBundleProducts($bundle, $request);

        return back()->with('status', 'Bundle updated successfully');
    }

    public function destroy(PosBundle $bundle)
    {
        $bundle->delete();
        return back()->with('status', 'Bundle deleted successfully');
    }

    private function syncBundleProducts(PosBundle $bundle, Request $request): void
    {
        $ids = $request->input('product_ids', []);
        $qtys = $request->input('product_qtys', []);

        $syncData = [];

        foreach ($ids as $idx => $pid) {
            $qty = isset($qtys[$idx]) ? (float) $qtys[$idx] : 0;
            if ($pid && $qty > 0) {
                $syncData[(int) $pid] = ['quantity' => $qty];
            }
        }

        $bundle->products()->sync($syncData);
    }
}
