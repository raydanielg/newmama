@extends('layouts.admin')

@section('title', 'Purchase Return')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Purchase Return</h3>
        <p>Return goods to supplier — reduces AP and stock.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.vouchers') }}" class="btn-primary" style="text-decoration:none;">Back to Vouchers</a>
    </div>
</div>

<div class="content-card">
    <form method="POST" action="{{ route('admin.vouchers.purchase-return.store') }}" style="padding: 16px;">
        @csrf

        <div class="content-card" style="margin-bottom:16px; background:#f9fafb; border:1px solid #eef2f7;">
            <div style="padding: 14px; display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Return Ref</label>
                    <input name="ref" value="{{ old('ref', $ref) }}" readonly style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; background:#f9fafb;">
                    @error('ref')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Date</label>
                    <input type="date" name="posting_date" value="{{ old('posting_date', now()->toDateString()) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    @error('posting_date')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Supplier</label>
                    <select name="supplier_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                        <option value="">— Select supplier —</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Original GRN Ref</label>
                    <input name="original_grn" value="{{ old('original_grn') }}" placeholder="GRN-0001" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    @error('original_grn')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div style="grid-column: span 2;">
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Return Reason</label>
                    @php($reasons = ['defective' => 'Defective / Not as described', 'wrong' => 'Wrong items sent', 'overdelivery' => 'Over-delivery', 'damaged' => 'Damaged in transit'])
                    <select name="reason" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                        @foreach($reasons as $k => $label)
                            <option value="{{ $k }}" {{ old('reason', 'defective') === $k ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('reason')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="content-card" style="margin-bottom:16px;">
            <div style="padding: 14px; display:flex; align-items:center; justify-content:space-between; gap:12px;">
                <div style="font-weight:700;">Items to Return</div>
                <button type="button" id="add_line" class="btn-icon">+ Add item</button>
            </div>
            @error('lines')<div style="color:#b91c1c; padding: 0 14px 14px;">{{ $message }}</div>@enderror

            <div class="table-responsive">
                <table class="admin-table" id="lines_table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th style="width: 90px; text-align:center;">Qty</th>
                            <th style="width: 140px; text-align:right;">Unit Cost</th>
                            <th style="width: 140px; text-align:right;">Amount</th>
                            <th style="width: 60px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($oldLines = old('lines') ?: [['product_id' => '', 'qty' => 1]])
                        @foreach($oldLines as $i => $l)
                        <tr>
                            <td>
                                <select name="lines[{{ $i }}][product_id]" class="line-product" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px;">
                                    <option value="">— Select product —</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}" data-cost="{{ (float) $p->cost_price }}" {{ ($l['product_id'] ?? '') == $p->id ? 'selected' : '' }}>{{ $p->sku }} — {{ $p->name }} · Stock: {{ (float) $p->qty_on_hand }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" min="0" step="0.01" name="lines[{{ $i }}][qty]" class="line-qty" value="{{ $l['qty'] ?? 1 }}" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px; text-align:center;">
                            </td>
                            <td class="td-right" style="font-weight:700;" data-role="unit">0</td>
                            <td class="td-right" style="font-weight:700;" data-role="amount">0</td>
                            <td class="td-right">
                                <button type="button" class="btn-icon line-remove">Remove</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 14px; display:flex; justify-content:flex-end;">
                <div style="min-width: 280px; background:#f9fafb; border:1px solid #eef2f7; border-radius:10px; padding: 12px 14px;">
                    <div style="display:flex; justify-content:space-between; font-weight:800;">
                        <span>Return Total</span>
                        <span id="return_total">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn-primary">Post Return</button>
        </div>
    </form>
</div>

<script>
(function(){
    const products = @json($products->map(fn($p) => ['id' => $p->id, 'sku' => $p->sku, 'name' => $p->name, 'cost' => (float) $p->cost_price, 'qty' => (float) $p->qty_on_hand])->values());
    const tbody = document.querySelector('#lines_table tbody');
    const addBtn = document.getElementById('add_line');
    const totalEl = document.getElementById('return_total');

    const recalc = function(){
        let total = 0;
        tbody.querySelectorAll('tr').forEach(tr => {
            const prodSel = tr.querySelector('.line-product');
            const qtyEl = tr.querySelector('.line-qty');
            const pid = prodSel ? prodSel.value : '';
            const p = products.find(x => String(x.id) === String(pid));
            const unit = p ? p.cost : 0;
            const qty = parseFloat(qtyEl ? qtyEl.value : '0');
            const amt = (qty * unit) || 0;
            total += amt;
            const unitCell = tr.querySelector('[data-role="unit"]');
            const amtCell = tr.querySelector('[data-role="amount"]');
            if (unitCell) unitCell.textContent = Math.round(unit).toLocaleString();
            if (amtCell) amtCell.textContent = Math.round(amt).toLocaleString();
        });
        if (totalEl) totalEl.textContent = Math.round(total).toLocaleString();
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
        const qtyEl = tr.querySelector('.line-qty');
        const removeBtn = tr.querySelector('.line-remove');
        if (prodSel) prodSel.addEventListener('change', recalc);
        if (qtyEl) qtyEl.addEventListener('input', recalc);
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
            const options = ['<option value="">— Select product —</option>'].concat(products.map(p => `<option value="${p.id}" data-cost="${p.cost}">${p.sku} — ${p.name} · Stock: ${p.qty}</option>`)).join('');
            tr.innerHTML = `
                <td><select name="lines[${i}][product_id]" class="line-product" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px;">${options}</select></td>
                <td><input type="number" min="0" step="0.01" name="lines[${i}][qty]" class="line-qty" value="1" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px; text-align:center;"></td>
                <td class="td-right" style="font-weight:700;" data-role="unit">0</td>
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
