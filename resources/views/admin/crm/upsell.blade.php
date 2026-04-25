@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Create campaigns and move them through draft → active → closed.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.crm.hub') }}">Back</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.crm.upsell') }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px;">
        <input name="q" value="{{ request('q') }}" placeholder="Search campaign" style="flex:1; min-width:220px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <select name="status" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            <option value="">All</option>
            @foreach(['draft' => 'Draft', 'active' => 'Active', 'closed' => 'Closed'] as $k => $v)
                <option value="{{ $k }}" {{ request('status')===$k?'selected':'' }}>{{ $v }}</option>
            @endforeach
        </select>
        <button class="btn-primary" type="submit">Filter</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.crm.upsell') }}">Reset</a>
    </form>

    <form method="POST" action="{{ route('admin.crm.upsell.store') }}" style="display:grid; grid-template-columns: 1fr 160px 1fr 140px; gap:10px; align-items:end;">
        @csrf
        <div>
            <label class="form-label">Campaign Name</label>
            <input name="name" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Channel</label>
            <select name="channel" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @foreach(['whatsapp','sms','email'] as $c)
                    <option value="{{ $c }}">{{ strtoupper($c) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Offer Text</label>
            <input name="offer_text" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <button class="btn-primary" type="submit">Create</button>
        </div>
    </form>
</div>

<div class="content-card" style="padding:16px;">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th style="width:140px;">Channel</th>
                    <th>Offer</th>
                    <th style="width:110px;">Status</th>
                    <th style="width:120px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaigns as $c)
                    <tr>
                        <td>{{ $c->name }}</td>
                        <td>{{ strtoupper($c->channel) }}</td>
                        <td>{{ $c->offer_text }}</td>
                        <td>{{ $c->status }}</td>
                        <td style="text-align:right;">
                            <form method="POST" action="{{ route('admin.crm.upsell.toggle', $c) }}" style="display:inline;">
                                @csrf
                                <button class="btn-icon" type="submit">Toggle</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center; color:#6b7280; padding:18px;">No campaigns found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $campaigns->links() }}</div>
</div>
@endsection
