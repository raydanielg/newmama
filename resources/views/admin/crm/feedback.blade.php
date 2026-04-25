@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Capture ratings and resolve customer feedback.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.crm.hub') }}">Back</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.crm.feedback') }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px;">
        <input name="q" value="{{ request('q') }}" placeholder="Search customer/message" style="flex:1; min-width:220px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <select name="status" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            <option value="">All</option>
            <option value="open" {{ request('status')==='open'?'selected':'' }}>Open</option>
            <option value="resolved" {{ request('status')==='resolved'?'selected':'' }}>Resolved</option>
        </select>
        <button class="btn-primary" type="submit">Filter</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.crm.feedback') }}">Reset</a>
    </form>

    <form method="POST" action="{{ route('admin.crm.feedback.store') }}" style="display:grid; grid-template-columns: 260px 160px 1fr 160px; gap:10px; align-items:end;">
        @csrf
        <div>
            <label class="form-label">Customer</label>
            <select name="customer_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">— Optional —</option>
                @foreach($customers as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Rating</label>
            <select name="rating" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @for($i=5; $i>=1; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label class="form-label">Message</label>
            <input name="message" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <button class="btn-primary" type="submit">Save</button>
        </div>
    </form>
</div>

<div class="content-card" style="padding:16px;">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:90px;">Status</th>
                    <th style="width:90px;">Rating</th>
                    <th>Message</th>
                    <th style="width:180px;">Customer</th>
                    <th style="width:120px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $e)
                    <tr>
                        <td>{{ $e->status }}</td>
                        <td>{{ (int) $e->rating }}/5</td>
                        <td>{{ $e->message }}</td>
                        <td>{{ optional($e->customer)->name ?: ($e->customer_name ?: '—') }}</td>
                        <td style="text-align:right;">
                            @if($e->status === 'open')
                                <form method="POST" action="{{ route('admin.crm.feedback.resolve', $e) }}" style="display:inline;">
                                    @csrf
                                    <button class="btn-icon" type="submit">Resolve</button>
                                </form>
                            @else
                                <span style="color:#6b7280;">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center; color:#6b7280; padding:18px;">No feedback found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $entries->links() }}</div>
</div>
@endsection
