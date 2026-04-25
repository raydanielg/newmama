@extends('layouts.admin')

@section('title', 'Cash Payment')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Cash Payment</h3>
        <p>Record a cash expense or supplier payment.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.vouchers') }}" class="btn-primary" style="text-decoration:none;">Back to Vouchers</a>
    </div>
</div>

<div class="content-card">
    <form method="POST" action="{{ route('admin.vouchers.cash-payment.store') }}" style="padding: 16px;">
        @csrf

        <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Voucher Ref</label>
                <input name="ref" value="{{ old('ref', $ref) }}" readonly style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; background:#f9fafb;">
                @error('ref')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>
            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Date</label>
                <input type="date" name="posting_date" value="{{ old('posting_date', now()->toDateString()) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('posting_date')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Supplier (optional)</label>
                <select name="supplier_id" id="supplier_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    <option value="">— Select supplier (optional) —</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" data-name="{{ $s->name }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }} · Balance: TZS {{ number_format((float) $s->balance_tzs, 0) }}</option>
                    @endforeach
                </select>
                @error('supplier_id')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Pay To (Payee)</label>
                <input name="pay_to" id="pay_to" value="{{ old('pay_to') }}" placeholder="e.g. Meditech Tanzania, John Msomi" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('pay_to')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Amount (TZS)</label>
                <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" placeholder="0" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; font-weight:700;">
                @error('amount')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Cheque / Reference No</label>
                <input name="cheque_no" value="{{ old('cheque_no') }}" placeholder="e.g. CHQ-001234 or M-Pesa ref" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('cheque_no')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Narration</label>
                <textarea name="narration" rows="3" placeholder="What was this payment for?" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; resize:none;">{{ old('narration') }}</textarea>
                @error('narration')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="content-card" style="margin-top:16px; background:#f9fafb; border:1px solid #eef2f7;">
            <div style="padding: 14px; display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Cash / Bank Account (Credit)</label>
                    <select name="cash_account_id" id="cash_account_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                        <option value="">— Select account —</option>
                        @foreach($cashAccounts as $a)
                            <option value="{{ $a->id }}" data-code="{{ $a->code }}" data-name="{{ $a->name }}" {{ old('cash_account_id') == $a->id ? 'selected' : '' }}>{{ $a->code }} — {{ $a->name }}</option>
                        @endforeach
                    </select>
                    @error('cash_account_id')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Expense / Debit Account</label>
                    <select name="exp_account_id" id="exp_account_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                        <option value="">— Select account —</option>
                        @foreach($expenseAccounts as $a)
                            <option value="{{ $a->id }}" data-code="{{ $a->code }}" data-name="{{ $a->name }}" {{ old('exp_account_id') == $a->id ? 'selected' : '' }}>{{ $a->code }} — {{ $a->name }}</option>
                        @endforeach
                    </select>
                    @error('exp_account_id')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Branch</label>
                    <select name="branch" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                        @php($branches = ['DSM HQ','Arusha Branch'])
                        @foreach($branches as $b)
                            <option value="{{ $b }}" {{ old('branch', 'DSM HQ') === $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                    @error('branch')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div style="display:flex; align-items:end;">
                    <button type="submit" class="btn-primary" style="width:100%; height:42px;">Post Payment</button>
                </div>

                <div style="grid-column: span 2;" id="journal_preview" class="content-card" style="display:none; margin:0;">
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
            </div>
        </div>
    </form>
</div>

<script>
(function(){
    const supplier = document.getElementById('supplier_id');
    const payTo = document.getElementById('pay_to');
    if (supplier && payTo) {
        supplier.addEventListener('change', function(){
            const opt = supplier.options[supplier.selectedIndex];
            const name = opt && opt.getAttribute('data-name');
            if (name) payTo.value = name;
        });
    }

    const amount = document.getElementById('amount');
    const cashAccount = document.getElementById('cash_account_id');
    const expAccount = document.getElementById('exp_account_id');

    const prev = document.getElementById('journal_preview');
    const prevDr = document.getElementById('prev_dr');
    const prevCr = document.getElementById('prev_cr');
    const prevAmt = document.getElementById('prev_amt');
    const prevAmt2 = document.getElementById('prev_amt2');

    const updatePreview = function(){
        if (!prev || !prevDr || !prevCr || !prevAmt || !prevAmt2) return;
        const amt = parseFloat((amount && amount.value) ? amount.value : '0');
        const cashOpt = cashAccount ? cashAccount.options[cashAccount.selectedIndex] : null;
        const expOpt = expAccount ? expAccount.options[expAccount.selectedIndex] : null;
        const cashText = cashOpt && cashOpt.value ? (cashOpt.getAttribute('data-code') + ' — ' + cashOpt.getAttribute('data-name')) : '';
        const expText = expOpt && expOpt.value ? (expOpt.getAttribute('data-code') + ' — ' + expOpt.getAttribute('data-name')) : '';

        if (amt > 0 && cashText && expText) {
            prev.style.display = 'block';
            prevDr.textContent = expText;
            prevCr.textContent = cashText;
            prevAmt.textContent = Math.round(amt).toLocaleString();
            prevAmt2.textContent = Math.round(amt).toLocaleString();
        } else {
            prev.style.display = 'none';
        }
    };

    if (amount) amount.addEventListener('input', updatePreview);
    if (cashAccount) cashAccount.addEventListener('change', updatePreview);
    if (expAccount) expAccount.addEventListener('change', updatePreview);
})();
</script>
@endsection
