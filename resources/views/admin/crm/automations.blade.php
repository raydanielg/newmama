@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Enable/disable automations and define basic triggers/actions.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.crm.hub') }}">Back</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.crm.automations') }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px;">
        <input name="q" value="{{ request('q') }}" placeholder="Search name" style="flex:1; min-width:220px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <select name="active" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            <option value="">All</option>
            <option value="1" {{ request('active')==='1'?'selected':'' }}>Active</option>
            <option value="0" {{ request('active')==='0'?'selected':'' }}>Inactive</option>
        </select>
        <button class="btn-primary" type="submit">Filter</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.crm.automations') }}">Reset</a>
    </form>

    <form method="POST" action="{{ route('admin.crm.automations.store') }}" style="display:grid; grid-template-columns: 1fr 220px 220px 120px; gap:10px; align-items:end;">
        @csrf
        <div>
            <label class="form-label">Name</label>
            <input name="name" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Trigger</label>
            <input name="trigger_type" required placeholder="e.g. preorder_created" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Action</label>
            <input name="action_type" required placeholder="e.g. send_whatsapp" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <button class="btn-primary" type="submit">Add</button>
        </div>
    </form>
</div>

<div class="content-card" style="padding:16px;">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th style="width:180px;">Trigger</th>
                    <th style="width:180px;">Action</th>
                    <th style="width:110px;">Active</th>
                    <th style="width:120px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($automations as $a)
                    <tr>
                        <td>{{ $a->name }}</td>
                        <td style="font-family:var(--mono);">{{ $a->trigger_type }}</td>
                        <td style="font-family:var(--mono);">{{ $a->action_type }}</td>
                        <td>{{ $a->is_active ? 'Yes' : 'No' }}</td>
                        <td style="text-align:right;">
                            <form method="POST" action="{{ route('admin.crm.automations.toggle', $a) }}" style="display:inline;">
                                @csrf
                                <button class="btn-icon" type="submit">Toggle</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center; color:#6b7280; padding:18px;">No automations created.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $automations->links() }}</div>
</div>
@endsection
