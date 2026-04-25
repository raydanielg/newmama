@extends('layouts.admin')

@section('title', 'Contra Entry')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Contra Entry</h3>
        <p>Cash deposit to bank or bank withdrawal to till.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.vouchers') }}" class="btn-primary" style="text-decoration:none;">Back to Vouchers</a>
    </div>
</div>

<div class="content-card">
    <form method="POST" action="{{ route('admin.vouchers.contra-entry.store') }}" style="padding: 16px;">
        @csrf

        <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Ref</label>
                <input name="ref" value="{{ old('ref', $ref) }}" readonly style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; background:#f9fafb;">
                @error('ref')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>
            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Date</label>
                <input type="date" name="posting_date" value="{{ old('posting_date', now()->toDateString()) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('posting_date')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">From (Source Account)</label>
                <select name="from_account_id" id="from_account_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    <option value="">— Select source —</option>
                    @foreach($accounts as $a)
                        <option value="{{ $a->id }}" data-code="{{ $a->code }}" data-name="{{ $a->name }}" {{ old('from_account_id') == $a->id ? 'selected' : '' }}>{{ $a->code }} — {{ $a->name }}</option>
                    @endforeach
                </select>
                @error('from_account_id')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">To (Destination Account)</label>
                <select name="to_account_id" id="to_account_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    <option value="">— Select destination —</option>
                    @foreach($accounts as $a)
                        <option value="{{ $a->id }}" data-code="{{ $a->code }}" data-name="{{ $a->name }}" {{ old('to_account_id') == $a->id ? 'selected' : '' }}>{{ $a->code }} — {{ $a->name }}</option>
                    @endforeach
                </select>
                @error('to_account_id')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Amount (TZS)</label>
                <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" placeholder="0" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; font-weight:700;">
                @error('amount')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Notes</label>
                <input name="notes" value="{{ old('notes') }}" placeholder="e.g. Cash deposited to CRDB from till" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('notes')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="grid-column: span 2;" id="journal_preview" class="content-card" style="display:none; margin:0; background:#f9fafb; border:1px solid #eef2f7;">
                <div style="padding: 14px;">
                    <div style="font-size:12px; font-weight:700; margin-bottom:8px;">Journal Preview</div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid #e5e7eb; padding:6px 0;">
                        <div><span style="font-weight:700;">Dr</span> <span id="prev_dr"></span></div>
                        <div style="font-weight:700;" id="prev_amt"></div>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding:6px 0;">
                        <div><span style="font-weight:700;">Cr</span> <span id="prev_cr"></span></div>
                        <div style="font-weight:700;" id="prev_amt2"></div>
                    </div>
                </div>
            </div>

            <div style="grid-column: span 2;">
                <button type="submit" class="btn-primary" style="width:100%; height:42px;">Post Contra</button>
            </div>
        </div>
    </form>
</div>

<script>
(function(){
    const amount = document.getElementById('amount');
    const fromAccount = document.getElementById('from_account_id');
    const toAccount = document.getElementById('to_account_id');

    const prev = document.getElementById('journal_preview');
    const prevDr = document.getElementById('prev_dr');
    const prevCr = document.getElementById('prev_cr');
    const prevAmt = document.getElementById('prev_amt');
    const prevAmt2 = document.getElementById('prev_amt2');

    const updatePreview = function(){
        if (!prev || !prevDr || !prevCr || !prevAmt || !prevAmt2) return;
        const amt = parseFloat((amount && amount.value) ? amount.value : '0');
        const fromOpt = fromAccount ? fromAccount.options[fromAccount.selectedIndex] : null;
        const toOpt = toAccount ? toAccount.options[toAccount.selectedIndex] : null;
        const fromText = fromOpt && fromOpt.value ? (fromOpt.getAttribute('data-code') + ' — ' + fromOpt.getAttribute('data-name')) : '';
        const toText = toOpt && toOpt.value ? (toOpt.getAttribute('data-code') + ' — ' + toOpt.getAttribute('data-name')) : '';

        if (amt > 0 && fromText && toText) {
            prev.style.display = 'block';
            prevDr.textContent = toText;
            prevCr.textContent = fromText;
            prevAmt.textContent = Math.round(amt).toLocaleString();
            prevAmt2.textContent = Math.round(amt).toLocaleString();
        } else {
            prev.style.display = 'none';
        }
    };

    if (amount) amount.addEventListener('input', updatePreview);
    if (fromAccount) fromAccount.addEventListener('change', updatePreview);
    if (toAccount) toAccount.addEventListener('change', updatePreview);
})();
</script>
@endsection
