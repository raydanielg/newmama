@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<style>
    .pos-container { display: grid; grid-template-columns: 1fr 380px; gap: 20px; height: calc(100vh - 140px); }
    .pos-main { display: flex; flex-direction: column; gap: 20px; overflow: hidden; }
    .pos-sidebar { background: #fff; border-radius: 12px; border: 1px solid rgba(17,24,39,0.1); display: flex; flex-direction: column; }
    
    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; overflow-y: auto; padding: 10px; }
    .product-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 12px; cursor: pointer; transition: all 0.2s; position: relative; }
    .product-card:hover { border-color: #2563eb; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .product-img { width: 100%; height: 100px; background: #f3f4f6; border-radius: 8px; margin-bottom: 8px; object-fit: cover; display: flex; align-items: center; justify-content: center; color: #9ca3af; }
    .product-name { font-weight: 700; font-size: 14px; margin-bottom: 4px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .product-price { color: #2563eb; font-weight: 800; font-size: 15px; }
    .product-stock { font-size: 11px; color: #6b7280; margin-top: 4px; }
    
    .cart-items { flex: 1; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 12px; }
    .cart-item { display: flex; justify-content: space-between; align-items: center; padding-bottom: 12px; border-bottom: 1px solid #f3f4f6; }
    .cart-item-info { flex: 1; }
    .cart-item-name { font-weight: 700; font-size: 13px; }
    .cart-item-price { font-size: 12px; color: #6b7280; }
    .cart-item-qty { display: flex; align-items: center; gap: 8px; margin-top: 4px; }
    .qty-btn { width: 24px; height: 24px; border-radius: 6px; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center; cursor: pointer; background: #fff; }
    
    .cart-summary { padding: 16px; background: #f9fafb; border-top: 1px solid #e5e7eb; border-radius: 0 0 12px 12px; }
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
    .summary-total { font-weight: 900; font-size: 18px; color: #111827; margin-top: 8px; padding-top: 8px; border-top: 2px dashed #e5e7eb; }
    
    .checkout-btn { width: 100%; padding: 14px; background: #2563eb; color: #fff; border: none; border-radius: 10px; font-weight: 800; margin-top: 12px; cursor: pointer; }
    
    .search-bar { background: #fff; padding: 12px; border-radius: 12px; border: 1px solid rgba(17,24,39,0.1); display: flex; gap: 10px; }
    .search-input { flex: 1; border: 1px solid #e5e7eb; border-radius: 8px; padding: 8px 12px; }
    
    .tabs { display: flex; gap: 10px; padding: 0 10px; }
    .tab { padding: 8px 16px; border-radius: 8px 8px 0 0; cursor: pointer; font-weight: 700; font-size: 13px; background: #e5e7eb; }
    .tab.active { background: #fff; border: 1px solid rgba(17,24,39,0.1); border-bottom: none; color: #2563eb; }

    .bundle-badge { position: absolute; top: 8px; right: 8px; background: #f59e0b; color: #fff; font-size: 10px; font-weight: 800; padding: 2px 6px; border-radius: 4px; }
</style>

<div class="pos-container">
    <div class="pos-main">
        <div class="search-bar">
            <input type="text" class="search-input" placeholder="Search product name or scan barcode..." id="posSearch">
            <select class="search-input" style="max-width: 200px;">
                <option value="">All Categories</option>
                <option value="nutrition">Nutrition</option>
                <option value="hygiene">Hygiene</option>
            </select>
        </div>

        <div class="tabs">
            <div class="tab active" data-tab="products">Products</div>
            <div class="tab" data-tab="bundles">Bundles / Packages</div>
        </div>

        <div class="content-card" style="flex: 1; overflow: hidden; padding: 0;">
            <div id="products-grid" class="product-grid">
                @foreach($products as $p)
                <div class="product-card" onclick="addToCart('product', {{ $p->id }}, '{{ $p->name }}', {{ $p->selling_price }}, '{{ $p->image_url }}')">
                    <div class="product-img">
                        @if($p->image_url)
                            <img src="{{ $p->image_url }}" alt="" style="width:100%; height:100%; object-fit:cover; border-radius:8px;">
                        @else
                            <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        @endif
                    </div>
                    <div class="product-name">{{ $p->name }}</div>
                    <div class="product-price">TSh {{ number_format($p->selling_price, 0) }}</div>
                    <div class="product-stock">Stock: {{ (int)$p->qty_on_hand }}</div>
                </div>
                @endforeach
            </div>
            
            <div id="bundles-grid" class="product-grid" style="display:none;">
                @foreach($bundles as $b)
                <div class="product-card" onclick="addToCart('bundle', {{ $b->id }}, '{{ $b->name }}', {{ $b->price }}, '{{ $b->image_url }}')">
                    <span class="bundle-badge">BUNDLE</span>
                    <div class="product-img">
                        @if($b->image_url)
                            <img src="{{ $b->image_url }}" alt="" style="width:100%; height:100%; object-fit:cover; border-radius:8px;">
                        @else
                            <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                        @endif
                    </div>
                    <div class="product-name">{{ $b->name }}</div>
                    <div class="product-price">TSh {{ number_format($b->price, 0) }}</div>
                    <div class="product-stock">{{ $b->products->count() }} Items</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="pos-sidebar">
        <div style="padding: 16px; border-bottom: 1px solid #f3f4f6;">
            <div style="margin-bottom: 12px;">
                <label style="display:block; font-size:12px; font-weight:700; color:#6b7280; margin-bottom:4px;">Customer / Mother</label>
                <select class="search-input" id="orderTarget" style="width:100%;">
                    <option value="">Walk-in Customer</option>
                    <optgroup label="Mothers">
                        @foreach($mothers as $m)
                            <option value="mother_{{ $m->id }}">{{ $m->full_name }} ({{ $m->whatsapp_number }})</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Regular Customers">
                        @foreach($customers as $c)
                            <option value="customer_{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
        </div>

        <div class="cart-items custom-scrollbar" id="cartItems">
            <div style="text-align:center; padding:40px; color:#9ca3af;">Cart is empty</div>
        </div>

        <div class="cart-summary">
            <div class="summary-row"><span>Subtotal</span><span id="sumSubtotal">TSh 0</span></div>
            <div class="summary-row"><span>Discount</span><input type="number" id="discountInput" value="0" style="width:80px; text-align:right; border:1px solid #e5e7eb; border-radius:4px;"></div>
            <div class="summary-total"><div class="summary-row"><span>Total</span><span id="sumTotal">TSh 0</span></div></div>
            
            <div style="margin-top:12px;">
                <label style="display:block; font-size:12px; font-weight:700; color:#6b7280; margin-bottom:4px;">Payment Method</label>
                <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:8px;">
                    <button class="qty-btn payment-method active" data-method="cash" style="width:100%; height:36px; font-size:11px; font-weight:700;">Cash</button>
                    <button class="qty-btn payment-method" data-method="mobile" style="width:100%; height:36px; font-size:11px; font-weight:700;">Mobile</button>
                    <button class="qty-btn payment-method" data-method="card" style="width:100%; height:36px; font-size:11px; font-weight:700;">Card</button>
                </div>
            </div>

            <button class="checkout-btn" onclick="checkout()">PLACE ORDER & PRINT</button>
        </div>
    </div>
</div>

<script>
    let cart = [];
    let currentPaymentMethod = 'cash';

    function addToCart(type, id, name, price, img) {
        const existing = cart.find(i => i.type === type && i.id === id);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({ type, id, name, price, img, qty: 1 });
        }
        renderCart();
    }

    function updateQty(index, delta) {
        cart[index].qty += delta;
        if (cart[index].qty <= 0) cart.splice(index, 1);
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cartItems');
        if (cart.length === 0) {
            container.innerHTML = '<div style="text-align:center; padding:40px; color:#9ca3af;">Cart is empty</div>';
            updateTotals();
            return;
        }

        container.innerHTML = cart.map((item, index) => `
            <div class="cart-item">
                <div class="cart-item-info">
                    <div class="cart-item-name">${item.name}</div>
                    <div class="cart-item-price">TSh ${item.price.toLocaleString()}</div>
                    <div class="cart-item-qty">
                        <button class="qty-btn" onclick="updateQty(${index}, -1)">-</button>
                        <span style="font-weight:800;">${item.qty}</span>
                        <button class="qty-btn" onclick="updateQty(${index}, 1)">+</button>
                    </div>
                </div>
                <div style="font-weight:800;">TSh ${(item.price * item.qty).toLocaleString()}</div>
            </div>
        `).join('');
        updateTotals();
    }

    function updateTotals() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        const discount = parseFloat(document.getElementById('discountInput').value) || 0;
        const total = Math.max(0, subtotal - discount);
        
        document.getElementById('sumSubtotal').textContent = `TSh ${subtotal.toLocaleString()}`;
        document.getElementById('sumTotal').textContent = `TSh ${total.toLocaleString()}`;
    }

    document.getElementById('discountInput').addEventListener('input', updateTotals);

    // Tab switching
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            const target = tab.dataset.tab;
            document.getElementById('products-grid').style.display = target === 'products' ? 'grid' : 'none';
            document.getElementById('bundles-grid').style.display = target === 'bundles' ? 'grid' : 'none';
        });
    });

    // Payment method selection
    document.querySelectorAll('.payment-method').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.payment-method').forEach(b => {
                b.classList.remove('active');
                b.style.borderColor = '#e5e7eb';
                b.style.background = '#fff';
            });
            btn.classList.add('active');
            btn.style.borderColor = '#2563eb';
            btn.style.background = '#eff6ff';
            currentPaymentMethod = btn.dataset.method;
        });
    });

    async function checkout() {
        if (cart.length === 0) return alert('Cart is empty');
        
        const target = document.getElementById('orderTarget').value;
        const payload = {
            customer_id: target.startsWith('customer_') ? target.replace('customer_', '') : null,
            mother_id: target.startsWith('mother_') ? target.replace('mother_', '') : null,
            items: cart.map(i => ({ type: i.type, id: i.id, qty: i.qty })),
            payment_method: currentPaymentMethod,
            notes: ''
        };

        try {
            const resp = await fetch('{{ route('admin.pos.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });
            const res = await resp.json();
            if (res.success) {
                if (confirm('Order placed successfully! View receipt?')) {
                    window.location.href = `/admin/pos/order/${res.order_id}/receipt`;
                } else {
                    cart = [];
                    renderCart();
                }
            }
        } catch (e) {
            alert('Error placing order');
        }
    }
</script>
@endsection
