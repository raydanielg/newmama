@extends('layouts.admin')

@section('title', $account->exists ? 'Edit Account' : 'Add Account')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $account->exists ? 'Edit Account' : 'Add Account' }}</h3>
        <p>Maintain your chart of accounts.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.chart-of-accounts') }}" class="btn-primary" style="text-decoration:none;">Back</a>
    </div>
</div>

<div class="content-card" style="padding:16px;">
    <form method="POST" action="{{ $account->exists ? route('admin.accounts.update', $account) : route('admin.accounts.store') }}" style="display:grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap:14px;">
        @csrf
        @if($account->exists) @method('PUT') @endif

        <div>
            <label style="display:block; font-weight:600; margin-bottom:6px;">Code</label>
            <input name="code" value="{{ old('code', $account->code) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            @error('code')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
        </div>

        <div>
            <label style="display:block; font-weight:600; margin-bottom:6px;">Name</label>
            <input name="name" value="{{ old('name', $account->name) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            @error('name')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
        </div>

        <div>
            <label style="display:block; font-weight:600; margin-bottom:6px;">Type</label>
            @php($types = ['asset','liability','equity','revenue','expense','cogs'])
            <select name="type" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @foreach($types as $t)
                    <option value="{{ $t }}" {{ old('type', $account->type) === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
            @error('type')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
        </div>

        <div>
            <label style="display:block; font-weight:600; margin-bottom:6px;">Category</label>
            <input name="category" value="{{ old('category', $account->category) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            @error('category')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
        </div>

        <div style="grid-column: span 2; display:flex; gap:10px; align-items:center;">
            <label style="display:flex; gap:8px; align-items:center;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $account->exists ? (int) $account->is_active : 1) ? 'checked' : '' }}>
                <span style="font-weight:600;">Active</span>
            </label>
            <div style="margin-left:auto; font-family:var(--mono); color:#6b7280;">Balance: {{ number_format((float) $account->balance, 2) }}</div>
        </div>

        <div style="grid-column: span 2;">
            <button class="btn-primary" type="submit" style="height:42px;">Save</button>
        </div>
    </form>
</div>
@endsection
