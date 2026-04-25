<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductRequest;
use App\Models\ProductRequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductSelectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);

        if ($request->has('category') && $request->category != 'all') {
            $query->where('category', $request->category);
        }

        $products = $query->get();
        $categories = Product::where('is_active', true)->distinct()->pluck('category');

        $selectedProductId = $request->query('id');
        
        // Fetch specific keys or all settings
        $settings = \App\Models\SystemSetting::all()->pluck('value', 'key');
        
        return view('landing.products', compact('products', 'categories', 'selectedProductId', 'settings'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|string',
            'payment_reference' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $totalAmount = 0;
            $itemsToSave = [];

            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['id']);
                $price = $product->selling_price;
                $subtotal = $price * $itemData['quantity'];
                $totalAmount += $subtotal;

                $itemsToSave[] = [
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $price,
                ];
            }

            // Create ProductRequest (Main record for this module)
            $token = Str::random(32);
            $productRequest = ProductRequest::create([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'payment_method' => $request->payment_method,
                'payment_reference' => $request->payment_reference,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_link_token' => $token,
                'expires_at' => Carbon::now()->addHours(24),
            ]);

            foreach ($itemsToSave as $item) {
                $productRequest->items()->create($item);
            }

            // Map to POS Order for admin tracking
            $customer = \App\Models\Customer::where('phone', $request->customer_phone)->first();
            if (!$customer) {
                $customer = \App\Models\Customer::create([
                    'name' => $request->customer_name,
                    'phone' => $request->customer_phone,
                ]);
            }

            $posOrder = \App\Models\PosOrder::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'customer_id' => $customer->id,
                'grand_total' => $totalAmount,
                'subtotal' => $totalAmount,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'unpaid',
                'payment_link_token' => $token,
                'expires_at' => Carbon::now()->addHours(24),
                'notes' => 'Order from Website Products Page. Customer: ' . $request->customer_name . ' (' . $request->customer_phone . ')',
            ]);

            foreach ($request->items as $itemData) {
                $product = Product::find($itemData['id']);
                $posOrder->items()->create([
                    'sellable_type' => Product::class,
                    'sellable_id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $product->selling_price,
                    'subtotal' => $product->selling_price * $itemData['quantity'],
                ]);
            }

            $paymentUrl = route('orders.status', ['token' => $token]);
            
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'redirect_url' => $paymentUrl,
                'token' => $token
            ]);
        });
    }

    public function status($token)
    {
        $order = \App\Models\PosOrder::with('items', 'customer')->where('payment_link_token', $token)->firstOrFail();
        $settings = \App\Models\SystemSetting::all()->pluck('value', 'key');
        
        return view('landing.order_status', compact('order', 'settings'));
    }

    public function processPayment(Request $request, $token)
    {
        $order = \App\Models\PosOrder::where('payment_link_token', $token)->firstOrFail();
        
        // Simulating payment success for now - in production this would verify with a provider
        $order->update([
            'payment_status' => 'paid',
            'status' => 'paid'
        ]);

        // Also update ProductRequest
        \App\Models\ProductRequest::where('payment_link_token', $token)->update(['status' => 'paid']);

        // Create Payment Record
        \App\Models\OrderPayment::create([
            'pos_order_id' => $order->id,
            'payment_method' => $order->payment_method,
            'amount' => $order->grand_total,
            'status' => 'completed',
            'paid_at' => now(),
            'reference' => 'SIM-' . Str::random(10)
        ]);

        return response()->json(['success' => true]);
    }
}
