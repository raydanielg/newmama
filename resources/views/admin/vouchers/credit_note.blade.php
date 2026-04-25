@extends('layouts.admin')

@section('title', 'Credit Note')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Credit Note</h3>
        <p>Credit customer — reduces their outstanding balance.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.vouchers') }}" class="btn-primary" style="text-decoration:none;">Back to Vouchers</a>
    </div>
</div>

<div class="content-card">
    <form method="POST" action="{{ route('admin.vouchers.credit-note.store') }}" style="padding: 16px;">
        @csrf

        <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Credit Note Ref</label>
                <input name="ref" value="{{ old('ref', $ref) }}" readonly style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; background:#f9fafb;">
                @error('ref')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>
            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Date</label>
                <input type="date" name="posting_date" value="{{ old('posting_date', now()->toDateString()) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('posting_date')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Customer (optional)</label>
                <select name="customer_id" id="customer_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    <option value="">— Select customer (optional) —</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" data-name="{{ $c->name }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->customer_number }} — {{ $c->name }} · Balance: {{ number_format((float) $c->balance, 0) }}</option>
                    @endforeach
                </select>
                @error('customer_id')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Customer Name</label>
                <input name="customer_name" id="customer_name" value="{{ old('customer_name') }}" placeholder="Customer name" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('customer_name')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Original Invoice Ref</label>
                <input name="original_inv" value="{{ old('original_inv') }}" placeholder="INV-0001" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('original_inv')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Credit Amount (TZS)</label>
                <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" placeholder="0" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; font-weight:700;">
                @error('amount')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Reason</label>
                @php($reasons = ['Overbilling correction','Discount granted after invoice','Goods returned','Goodwill credit','Price adjustment'])
                <select name="reason" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    <option value="">— Select reason —</option>
                    @foreach($reasons as $r)
                        <option value="{{ $r }}" {{ old('reason') === $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
                @error('reason')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Notes</label>
                <textarea name="notes" rows="2" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; resize:none;">{{ old('notes') }}</textarea>
                @error('notes')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="grid-column: span 2;" id="journal_preview" class="content-card" style="display:none; margin:0; background:#f9fafb; border:1px solid #eef2f7;">
                <div style="padding: 14px;">
                    <div style="font-size:12px; font-weight:700; margin-bottom:8px;">Journal Preview</div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid #e5e7eb; padding:6px 0;">
                        <div><span style="font-weight:700;">Dr</span> <span>Revenue (4010)</span></div>
                        <div style="font-weight:700;" id="prev_amt"></div>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding:6px 0;">
                        <div><span style="font-weight:700;">Cr</span> <span>AR (1050)</span></div>
                        <div style="font-weight:700;" id="prev_amt2"></div>
                    </div>
                </div>
            </div>

            <div style="grid-column: span 2;">
                <button type="submit" class="btn-primary" style="width:100%; height:42px;">Post Credit Note</button>
            </div>
        </div>
    </form>
</div>

<script>
(function(){
    const customer = document.getElementById('customer_id');
    const customerName = document.getElementById('customer_name');
    if (customer && customerName) {
        customer.addEventListener('change', function(){
            const opt = customer.options[customer.selectedIndex];
            const name = opt && opt.getAttribute('data-name');
            if (name) customerName.value = name;
        });
    }

    const amount = document.getElementById('amount');
    const prev = document.getElementById('journal_preview');
    const prevAmt = document.getElementById('prev_amt');
    const prevAmt2 = document.getElementById('prev_amt2');

    const updatePreview = function(){
        if (!prev || !prevAmt || !prevAmt2) return;
        const amt = parseFloat((amount && amount.value) ? amount.value : '0');
        if (amt > 0) {
            prev.style.display = 'block';
            prevAmt.textContent = Math.round(amt).toLocaleString();
            prevAmt2.textContent = Math.round(amt).toLocaleString();
        } else {
            prev.style.display = 'none';
        }
    };

    if (amount) amount.addEventListener('input', updatePreview);
    updatePreview();
})();
</script>
@endsection
