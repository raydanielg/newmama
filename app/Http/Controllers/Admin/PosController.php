<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PosBundle;
use App\Models\PosOrder;
use App\Models\Customer;
use App\Models\Mother;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->get();
        $bundles = PosBundle::where('is_active', true)->with('products')->get();
        $customers = Customer::orderBy('name')->get();
        $mothers = Mother::orderBy('full_name')->get();
        
        $recentOrders = PosOrder::with(['customer', 'mother'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.pos.index', [
            'title' => 'Point of Sale',
            'products' => $products,
            'bundles' => $bundles,
            'customers' => $customers,
            'mothers' => $mothers,
            'recentOrders' => $recentOrders,
        ]);
    }

    public function orders()
    {
        $orders = PosOrder::with(['customer', 'mother'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.pos.orders', [
            'title' => 'POS Orders',
            'orders' => $orders,
        ]);
    }

    public function storeOrder(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'mother_id' => 'nullable|exists:mothers,id',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:product,bundle',
            'items.*.id' => 'required|integer',
            'items.*.qty' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($data) {
            $order = PosOrder::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'customer_id' => $data['customer_id'],
                'mother_id' => $data['mother_id'],
                'payment_method' => $data['payment_method'],
                'status' => 'paid',
                'payment_status' => 'paid',
                'notes' => $data['notes'],
                'created_by' => auth()->id(),
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {
                $sellable = $item['type'] === 'product' 
                    ? Product::find($item['id']) 
                    : PosBundle::find($item['id']);

                if (!$sellable) continue;

                $price = $item['type'] === 'product' ? $sellable->selling_price : $sellable->price;
                $subtotal = $price * $item['qty'];

                $order->items()->create([
                    'sellable_type' => get_class($sellable),
                    'sellable_id' => $sellable->id,
                    'name' => $sellable->name,
                    'quantity' => $item['qty'],
                    'unit_price' => $price,
                    'subtotal' => $subtotal,
                ]);

                // Stock decrement for products
                if ($item['type'] === 'product') {
                    $sellable->decrement('qty_on_hand', $item['qty']);
                } else {
                    foreach ($sellable->products as $p) {
                        $p->decrement('qty_on_hand', $p->pivot->quantity * $item['qty']);
                    }
                }

                $total += $subtotal;
            }

            $order->update([
                'subtotal' => $total,
                'grand_total' => $total,
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Order placed successfully'
            ]);
        });
    }

    public function showReceipt(PosOrder $order)
    {
        $order->load(['items', 'customer', 'mother.country', 'mother.region', 'mother.district', 'creator']);
        return view('admin.pos.receipt', [
            'order' => $order,
            'title' => 'Order Receipt #' . $order->order_number
        ]);
    }
}
