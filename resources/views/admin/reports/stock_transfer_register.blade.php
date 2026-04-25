@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Transfers posted with type <span style="font-family:var(--mono); font-weight:700;">stock_transfer</span>.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.reports') }}">Back</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.reports.stock-transfer-register') }}" style="display:flex; gap:10px; flex-wrap:wrap;">
        <input name="q" value="{{ request('q') }}" placeholder="Search ref/description" style="flex:1; min-width:220px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <input type="date" name="from" value="{{ $from }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <input type="date" name="to" value="{{ $to }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <button class="btn-primary" type="submit">Run</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.reports.stock-transfer-register') }}">Reset</a>
    </form>
</div>

<div class="content-card" style="padding:16px;">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:140px;">Date</th>
                    <th style="width:160px;">Ref</th>
                    <th>Description</th>
                    <th style="width:120px;">Status</th>
                    <th style="width:120px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($vouchers as $v)
                    <tr>
                        <td>{{ optional($v->posting_date)->toDateString() }}</td>
                        <td style="font-family:var(--mono); font-weight:700;">{{ $v->ref }}</td>
                        <td>{{ $v->description }}</td>
                        <td>{{ $v->status }}</td>
                        <td style="text-align:right;">
                            <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.vouchers.view', $v) }}">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center; color:#6b7280; padding:18px;">No stock transfers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $vouchers->links() }}</div>
</div>
@endsection
