@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Log inbound messages and track resolution.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.crm.hub') }}">Back</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.crm.inbox') }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px;">
        <input name="q" value="{{ request('q') }}" placeholder="Search subject/body" style="flex:1; min-width:220px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <select name="status" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            <option value="">All Status</option>
            <option value="open" {{ request('status')==='open'?'selected':'' }}>Open</option>
            <option value="closed" {{ request('status')==='closed'?'selected':'' }}>Closed</option>
        </select>
        <select name="channel" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            <option value="">All Channels</option>
            @foreach(['whatsapp','sms','email','call'] as $c)
                <option value="{{ $c }}" {{ request('channel')===$c?'selected':'' }}>{{ strtoupper($c) }}</option>
            @endforeach
        </select>
        <button class="btn-primary" type="submit">Filter</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.crm.inbox') }}">Reset</a>
    </form>

    <form method="POST" action="{{ route('admin.crm.inbox.store') }}" style="display:grid; grid-template-columns: 220px 140px 160px 1fr 140px; gap:10px; align-items:end;">
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
            <label class="form-label">Channel</label>
            <select name="channel" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @foreach(['whatsapp','sms','email','call'] as $c)
                    <option value="{{ $c }}">{{ strtoupper($c) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Priority</label>
            <select name="priority" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @foreach(['low','normal','high','urgent'] as $p)
                    <option value="{{ $p }}">{{ strtoupper($p) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Subject</label>
            <input name="subject" placeholder="Optional" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <button class="btn-primary" type="submit">Log Message</button>
        </div>
        <div style="grid-column: 1 / -1;">
            <label class="form-label">Message</label>
            <textarea name="body" required rows="2" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;"></textarea>
        </div>
    </form>
</div>

<div class="content-card" style="padding:16px;">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:90px;">Status</th>
                    <th style="width:110px;">Channel</th>
                    <th>Subject</th>
                    <th style="width:180px;">Customer</th>
                    <th style="width:120px;">Priority</th>
                    <th style="width:120px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $m)
                    <tr>
                        <td>{{ $m->status }}</td>
                        <td>{{ strtoupper($m->channel) }}</td>
                        <td>{{ $m->subject ?: \Illuminate\Support\Str::limit($m->body, 60) }}</td>
                        <td>
                            @if($m->customer)
                                <a style="text-decoration:none;" href="{{ route('admin.customers.ledger', $m->customer) }}">{{ $m->customer->name }}</a>
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ strtoupper($m->priority) }}</td>
                        <td style="text-align:right;">
                            @if($m->status === 'open')
                                <form method="POST" action="{{ route('admin.crm.inbox.close', $m) }}" style="display:inline;">
                                    @csrf
                                    <button class="btn-icon" type="submit">Close</button>
                                </form>
                            @else
                                <span style="color:#6b7280;">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center; color:#6b7280; padding:18px;">No messages found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $messages->links() }}</div>
</div>
@endsection
