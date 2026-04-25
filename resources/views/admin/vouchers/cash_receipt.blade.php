@extends('layouts.admin')

@section('title', 'Cash Receipt')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Cash Receipt</h3>
        <p>Record money received in cash or M-Pesa.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.vouchers') }}" class="btn-primary" style="text-decoration:none;">Back to Vouchers</a>
    </div>
</div>

<div class="content-card">
    <form method="POST" action="{{ route('admin.vouchers.cash-receipt.store') }}" style="padding: 16px;">
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
                <label style="display:block; font-weight:600; margin-bottom:6px;">Received From</label>
                <input name="received_from" id="received_from" value="{{ old('received_from') }}" placeholder="e.g. Amina Hassan, Aga Khan Hospital" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('received_from')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Amount (TZS)</label>
                <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" placeholder="0" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; font-weight:700;">
                @error('amount')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Payment Method</label>
                @php($methods = ['cash' => 'Cash', 'mpesa' => 'M-Pesa', 'bank' => 'Bank Transfer', 'pos' => 'POS Card'])
                <select name="payment_method" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    @foreach($methods as $k => $label)
                        <option value="{{ $k }}" {{ old('payment_method', 'cash') === $k ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('payment_method')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Narration</label>
                <textarea name="narration" rows="3" placeholder="What is this payment for?" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; resize:none;">{{ old('narration') }}</textarea>
                @error('narration')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="content-card" style="margin-top:16px; background:#f9fafb; border:1px solid #eef2f7;">
            <div style="padding: 14px; display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Deposit To (Debit Account)</label>
                    <select name="cash_account_id" id="cash_account_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                        <option value="">— Select account —</option>
                        @foreach($cashAccounts as $a)
                            <option value="{{ $a->id }}" data-code="{{ $a->code }}" data-name="{{ $a->name }}" {{ old('cash_account_id') == $a->id ? 'selected' : '' }}>{{ $a->code }} — {{ $a->name }}</option>
                        @endforeach
                    </select>
                    @error('cash_account_id')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Income / Credit Account</label>
                    <select name="credit_account_id" id="credit_account_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                        <option value="">— Select account —</option>
                        @foreach($creditAccounts as $a)
                            <option value="{{ $a->id }}" data-code="{{ $a->code }}" data-name="{{ $a->name }}" {{ old('credit_account_id') == $a->id ? 'selected' : '' }}>{{ $a->code }} — {{ $a->name }}</option>
                        @endforeach
                    </select>
                    @error('credit_account_id')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
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

                <div style="grid-column: span 2;">
                    <button type="submit" class="btn-primary" style="width:100%; height:42px;">Post Receipt</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
(function(){
    const customer = document.getElementById('customer_id');
    const received = document.getElementById('received_from');
    if (customer && received) {
        customer.addEventListener('change', function(){
            const opt = customer.options[customer.selectedIndex];
            const name = opt && opt.getAttribute('data-name');
            if (name) received.value = name;
        });
    }

    const amount = document.getElementById('amount');
    const cashAccount = document.getElementById('cash_account_id');
    const creditAccount = document.getElementById('credit_account_id');

    const prev = document.getElementById('journal_preview');
    const prevDr = document.getElementById('prev_dr');
    const prevCr = document.getElementById('prev_cr');
    const prevAmt = document.getElementById('prev_amt');
    const prevAmt2 = document.getElementById('prev_amt2');

    const updatePreview = function(){
        if (!prev || !prevDr || !prevCr || !prevAmt || !prevAmt2) return;
        const amt = parseFloat((amount && amount.value) ? amount.value : '0');
        const cashOpt = cashAccount ? cashAccount.options[cashAccount.selectedIndex] : null;
        const creditOpt = creditAccount ? creditAccount.options[creditAccount.selectedIndex] : null;
        const cashText = cashOpt && cashOpt.value ? (cashOpt.getAttribute('data-code') + ' — ' + cashOpt.getAttribute('data-name')) : '';
        const creditText = creditOpt && creditOpt.value ? (creditOpt.getAttribute('data-code') + ' — ' + creditOpt.getAttribute('data-name')) : '';

        if (amt > 0 && cashText && creditText) {
            prev.style.display = 'block';
            prevDr.textContent = cashText;
            prevCr.textContent = creditText;
            prevAmt.textContent = Math.round(amt).toLocaleString();
            prevAmt2.textContent = Math.round(amt).toLocaleString();
        } else {
            prev.style.display = 'none';
        }
    };

    if (amount) amount.addEventListener('input', updatePreview);
    if (cashAccount) cashAccount.addEventListener('change', updatePreview);
    if (creditAccount) creditAccount.addEventListener('change', updatePreview);
})();
</script>
@endsection
