@extends('layouts.admin')

@section('title', 'Sales Invoice')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Sales Invoice</h3>
        <p>Create a debtor invoice — posts AR, Revenue, VAT, COGS and reduces stock.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.vouchers') }}" class="btn-primary" style="text-decoration:none;">Back to Vouchers</a>
    </div>
</div>

<div class="content-card">
    <form method="POST" action="{{ route('admin.vouchers.sales-invoice.store') }}" style="padding: 16px;">
        @csrf

        <div class="content-card" style="margin-bottom:16px; background:#f9fafb; border:1px solid #eef2f7;">
            <div style="padding: 14px; display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Invoice Ref</label>
                    <input name="ref" value="{{ old('ref', $ref) }}" readonly style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; background:#f9fafb;">
                    @error('ref')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Date</label>
                    <input type="date" name="posting_date" value="{{ old('posting_date', now()->toDateString()) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    @error('posting_date')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div style="grid-column: span 2;">
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Customer (Debtor)</label>
                    <select name="customer_id" id="customer_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                        <option value="">— Select debtor customer —</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" data-name="{{ $c->name }}" data-credit="{{ (float) $c->credit_limit }}" data-balance="{{ (float) $c->balance }}" data-period="{{ (int) $c->credit_period }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->customer_number }} — {{ $c->name }} · Balance: {{ number_format((float) $c->balance, 0) }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Due Date</label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    @error('due_date')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Payment Terms</label>
                    <input name="payment_terms" value="{{ old('payment_terms', 'NET30') }}" placeholder="NET30" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    @error('payment_terms')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div style="grid-column: span 2;">
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Notes</label>
                    <textarea name="notes" rows="2" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; resize:none;">{{ old('notes') }}</textarea>
                    @error('notes')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="content-card" style="margin-bottom:16px;">
            <div style="padding: 14px; display:flex; align-items:center; justify-content:space-between; gap:12px;">
                <div style="font-weight:700;">Invoice Lines</div>
                <button type="button" id="add_line" class="btn-icon">+ Add Line</button>
            </div>
            @error('lines')<div style="color:#b91c1c; padding: 0 14px 14px;">{{ $message }}</div>@enderror

            <div class="table-responsive">
                <table class="admin-table" id="lines_table">
                    <thead>
                        <tr>
                            <th style="width: 240px;">Product</th>
                            <th style="width: 100px; text-align:center;">Qty</th>
                            <th style="width: 140px; text-align:right;">Unit Price</th>
                            <th style="width: 120px; text-align:right;">Discount %</th>
                            <th style="width: 140px; text-align:right;">Amount</th>
                            <th style="width: 60px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($oldLines = old('lines') ?: [['product_id' => '', 'qty' => 1, 'unit_price' => 0, 'discount_pct' => 0]])
                        @foreach($oldLines as $i => $l)
                        <tr>
                            <td>
                                <select name="lines[{{ $i }}][product_id]" class="line-product" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px;">
                                    <option value="">— Select product —</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}" data-name="{{ $p->name }}" data-cost="{{ (float) $p->cost_price }}" data-price="{{ (float) $p->selling_price }}" data-stock="{{ (float) $p->qty_on_hand }}" {{ ($l['product_id'] ?? '') == $p->id ? 'selected' : '' }}>
                                            {{ $p->sku }} — {{ $p->name }} · Stock: {{ (float) $p->qty_on_hand }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" min="0" step="0.01" name="lines[{{ $i }}][qty]" class="line-qty" value="{{ $l['qty'] ?? 1 }}" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px; text-align:center;">
                            </td>
                            <td>
                                <input type="number" min="0" step="0.01" name="lines[{{ $i }}][unit_price]" class="line-price" value="{{ $l['unit_price'] ?? 0 }}" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px; text-align:right; font-weight:700;">
                            </td>
                            <td>
                                <input type="number" min="0" step="0.01" name="lines[{{ $i }}][discount_pct]" class="line-disc" value="{{ $l['discount_pct'] ?? 0 }}" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px; text-align:right;">
                            </td>
                            <td class="td-right" style="font-weight:700;" data-role="amount">0</td>
                            <td class="td-right"><button type="button" class="btn-icon line-remove">Remove</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 14px; display:flex; justify-content:flex-end;">
                <div style="min-width: 320px; background:#f9fafb; border:1px solid #eef2f7; border-radius:10px; padding: 12px 14px;">
                    <div style="display:flex; justify-content:space-between; font-weight:800;">
                        <span>Subtotal (Gross)</span>
                        <span id="inv_subtotal">0</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-top:6px;">
                        <span style="color:#6b7280;">VAT (included)</span>
                        <span id="inv_vat">0</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-top:6px;">
                        <span style="color:#6b7280;">Net Revenue</span>
                        <span id="inv_net">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn-primary">Post Invoice</button>
        </div>
    </form>
</div>

<script>
(function(){
    const products = {!! $products->map(function ($p) {
        return [
            'id' => $p->id,
            'sku' => $p->sku,
            'name' => $p->name,
            'price' => (float) $p->selling_price,
            'cost' => (float) $p->cost_price,
            'stock' => (float) $p->qty_on_hand,
        ];
    })->values()->toJson() !!};
    const tbody = document.querySelector('#lines_table tbody');
    const addBtn = document.getElementById('add_line');

    const elSub = document.getElementById('inv_subtotal');
    const elVat = document.getElementById('inv_vat');
    const elNet = document.getElementById('inv_net');

    const calc = (subtotal) => {
        const vat = Math.round(subtotal * 18 / 118);
        const net = Math.round(subtotal - vat);
        return { vat, net };
    };

    const recalc = function(){
        let subtotal = 0;
        tbody.querySelectorAll('tr').forEach(tr => {
            const qty = parseFloat(tr.querySelector('.line-qty')?.value || '0');
            const price = parseFloat(tr.querySelector('.line-price')?.value || '0');
            const disc = parseFloat(tr.querySelector('.line-disc')?.value || '0');
            const amt = Math.round(price * qty * (1 - (disc/100)));
            subtotal += (amt || 0);
            const cell = tr.querySelector('[data-role="amount"]');
            if (cell) cell.textContent = Math.round(amt || 0).toLocaleString();
        });
        const { vat, net } = calc(subtotal);
        if (elSub) elSub.textContent = Math.round(subtotal).toLocaleString();
        if (elVat) elVat.textContent = Math.round(vat).toLocaleString();
        if (elNet) elNet.textContent = Math.round(net).toLocaleString();
    };

    const reindex = function(){
        tbody.querySelectorAll('tr').forEach((tr, i) => {
            tr.querySelectorAll('select, input').forEach(el => {
                const name = el.getAttribute('name');
                if (!name) return;
                el.setAttribute('name', name.replace(/lines\[\d+\]/, 'lines[' + i + ']'));
            });
        });
    };

    const bindRow = function(tr){
        const prodSel = tr.querySelector('.line-product');
        const price = tr.querySelector('.line-price');
        const qty = tr.querySelector('.line-qty');
        const disc = tr.querySelector('.line-disc');
        const removeBtn = tr.querySelector('.line-remove');

        if (prodSel && price) {
            prodSel.addEventListener('change', function(){
                const opt = prodSel.options[prodSel.selectedIndex];
                const p = opt && opt.getAttribute('data-price');
                if (p) price.value = String(p);
                recalc();
            });
        }
        if (qty) qty.addEventListener('input', recalc);
        if (price) price.addEventListener('input', recalc);
        if (disc) disc.addEventListener('input', recalc);
        if (removeBtn) removeBtn.addEventListener('click', function(){
            tr.remove();
            reindex();
            recalc();
        });
    };

    tbody.querySelectorAll('tr').forEach(bindRow);

    if (addBtn) {
        addBtn.addEventListener('click', function(){
            const i = tbody.querySelectorAll('tr').length;
            const tr = document.createElement('tr');
            const options = ['<option value="">— Select product —</option>'].concat(products.map(p => `<option value="${p.id}" data-price="${p.price}" data-cost="${p.cost}" data-stock="${p.stock}">${p.sku} — ${p.name} · Stock: ${p.stock}</option>`)).join('');
            tr.innerHTML = `
                <td><select name="lines[${i}][product_id]" class="line-product" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px;">${options}</select></td>
                <td><input type="number" min="0" step="0.01" name="lines[${i}][qty]" class="line-qty" value="1" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px; text-align:center;"></td>
                <td><input type="number" min="0" step="0.01" name="lines[${i}][unit_price]" class="line-price" value="0" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px; text-align:right; font-weight:700;"></td>
                <td><input type="number" min="0" step="0.01" name="lines[${i}][discount_pct]" class="line-disc" value="0" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px; text-align:right;"></td>
                <td class="td-right" style="font-weight:700;" data-role="amount">0</td>
                <td class="td-right"><button type="button" class="btn-icon line-remove">Remove</button></td>
            `;
            tbody.appendChild(tr);
            bindRow(tr);
            recalc();
        });
    }

    recalc();
})();
</script>
@endsection
