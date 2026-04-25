@extends('layouts.admin')

@section('title', 'Purchase Invoice')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Purchase Invoice</h3>
        <p>Match supplier invoice to GRN — clears 1121, creates AP entry.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.vouchers') }}" class="btn-primary" style="text-decoration:none;">Back to Vouchers</a>
    </div>
</div>

<div class="content-card">
    <form method="POST" action="{{ route('admin.vouchers.purchase-invoice.store') }}" style="padding: 16px;">
        @csrf

        <div class="content-card" style="margin-bottom:16px; background:#f9fafb; border:1px solid #eef2f7;">
            <div style="padding: 14px; display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Invoice No</label>
                    <input name="ref" value="{{ old('ref', $ref) }}" readonly style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; background:#f9fafb;">
                    @error('ref')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Invoice Date</label>
                    <input type="date" name="posting_date" value="{{ old('posting_date', now()->toDateString()) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    @error('posting_date')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    @error('due_date')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Supplier</label>
                    <select name="supplier_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                        <option value="">— Select supplier —</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }} — Balance: TZS {{ number_format((float) $s->balance_tzs, 0) }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Supplier Invoice Reference</label>
                    <input name="supplier_ref" value="{{ old('supplier_ref') }}" placeholder="Supplier's own invoice number" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    @error('supplier_ref')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Related PO Ref</label>
                    <input name="po_ref" value="{{ old('po_ref') }}" placeholder="PO-0001" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    @error('po_ref')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Related GRN Ref</label>
                    <input name="grn_ref" value="{{ old('grn_ref') }}" placeholder="GRN-0001" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    @error('grn_ref')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
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
                            <th style="width: 220px;">Product</th>
                            <th>Description</th>
                            <th style="width: 90px; text-align:center;">Qty</th>
                            <th style="width: 140px; text-align:right;">Unit Cost</th>
                            <th style="width: 140px; text-align:right;">Amount</th>
                            <th style="width: 60px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($oldLines = old('lines') ?: [['product_id' => '', 'description' => '', 'qty' => 1, 'unit_cost' => 0]])
                        @foreach($oldLines as $i => $l)
                        <tr>
                            <td>
                                <select name="lines[{{ $i }}][product_id]" class="line-product" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px;">
                                    <option value="">— Select product —</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}" data-name="{{ $p->name }}" data-cost="{{ (float) $p->cost_price }}" {{ ($l['product_id'] ?? '') == $p->id ? 'selected' : '' }}>{{ $p->sku }} — {{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input name="lines[{{ $i }}][description]" class="line-desc" value="{{ $l['description'] ?? '' }}" placeholder="Description" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px;">
                            </td>
                            <td>
                                <input type="number" min="0" step="0.01" name="lines[{{ $i }}][qty]" class="line-qty" value="{{ $l['qty'] ?? 1 }}" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px; text-align:center;">
                            </td>
                            <td>
                                <input type="number" min="0" step="0.01" name="lines[{{ $i }}][unit_cost]" class="line-unit" value="{{ $l['unit_cost'] ?? 0 }}" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px; text-align:right; font-weight:700;">
                            </td>
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
                        <span>Invoice Total</span>
                        <span id="invoice_total">0</span>
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
    const products = @json($products->map(fn($p) => ['id' => $p->id, 'sku' => $p->sku, 'name' => $p->name, 'cost' => (float) $p->cost_price])->values());
    const tbody = document.querySelector('#lines_table tbody');
    const addBtn = document.getElementById('add_line');
    const totalEl = document.getElementById('invoice_total');

    const recalc = function(){
        let total = 0;
        tbody.querySelectorAll('tr').forEach(tr => {
            const qty = parseFloat(tr.querySelector('.line-qty')?.value || '0');
            const unit = parseFloat(tr.querySelector('.line-unit')?.value || '0');
            const amt = (qty * unit) || 0;
            total += amt;
            const cell = tr.querySelector('[data-role="amount"]');
            if (cell) cell.textContent = Math.round(amt).toLocaleString();
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
        const desc = tr.querySelector('.line-desc');
        const qty = tr.querySelector('.line-qty');
        const unit = tr.querySelector('.line-unit');
        const removeBtn = tr.querySelector('.line-remove');

        if (prodSel && desc && unit) {
            prodSel.addEventListener('change', function(){
                const opt = prodSel.options[prodSel.selectedIndex];
                const n = opt && opt.getAttribute('data-name');
                const c = opt && opt.getAttribute('data-cost');
                if (n && !desc.value) desc.value = n;
                if (c) unit.value = String(c);
                recalc();
            });
        }
        if (qty) qty.addEventListener('input', recalc);
        if (unit) unit.addEventListener('input', recalc);
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

            const options = ['<option value="">— Select product —</option>'].concat(products.map(p => `<option value="${p.id}" data-name="${p.name}" data-cost="${p.cost}">${p.sku} — ${p.name}</option>`)).join('');

            tr.innerHTML = `
                <td>
                    <select name="lines[${i}][product_id]" class="line-product" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px;">${options}</select>
                </td>
                <td><input name="lines[${i}][description]" class="line-desc" value="" placeholder="Description" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px;"></td>
                <td><input type="number" min="0" step="0.01" name="lines[${i}][qty]" class="line-qty" value="1" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px; text-align:center;"></td>
                <td><input type="number" min="0" step="0.01" name="lines[${i}][unit_cost]" class="line-unit" value="0" style="width:100%; padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px; text-align:right; font-weight:700;"></td>
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
