@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<style>
    .btn-ico-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
        text-decoration: none;
        cursor: pointer;
    }
    .btn-ico-circle:hover { background: #e5e7eb; color: #111827; }
    .btn-ico-circle.edit:hover { background: #dbeafe; color: #2563eb; border-color: #bfdbfe; }
    .btn-ico-circle.delete:hover { background: #fee2e2; color: #dc2626; border-color: #fecaca; }

    .modal-overlay { position: fixed; inset: 0; background: rgba(17,24,39,0.55); display: none; align-items: center; justify-content: center; padding: 20px; z-index: 9999; }
    .modal { width: 100%; max-width: 900px; background: #fff; border-radius: 14px; overflow: hidden; border: 1px solid rgba(17,24,39,0.1); box-shadow: 0 20px 50px rgba(0,0,0,0.2); }
    .modal-header { padding: 16px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .modal-title { font-size: 16px; font-weight: 900; }
    .modal-body { padding: 16px; }
    .grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
    .field label { display:block; font-size: 12px; font-weight: 800; color:#6b7280; margin-bottom: 6px; }
    .field input, .field select { width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 10px; }
    .modal-footer { padding: 16px; display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e5e7eb; }
    .btn-secondary { padding: 10px 14px; border-radius: 10px; border: 1px solid #e5e7eb; background: #fff; font-weight: 800; cursor: pointer; }
</style>

<div class="module-header">
    <div class="header-info">
        <h3>HRM Assets</h3>
        <p>Track company assets, assignments, and maintenance.</p>
    </div>
    <div class="header-actions" style="display:flex; gap:10px;">
        <button class="btn-primary" type="button" onclick="openCreateModal()">Add Asset</button>
    </div>
</div>

@if(session('status'))
    <div class="content-card" style="padding:12px; border:1px solid #bbf7d0; background:#f0fdf4; color:#166534; margin-bottom:14px;">{{ session('status') }}</div>
@endif

@if($errors->any())
    <div class="content-card" style="padding:12px; border:1px solid #fecaca; background:#fef2f2; color:#991b1b; margin-bottom:14px;">
        <div style="font-weight:900; margin-bottom:6px;">Please fix the errors below:</div>
        <ul style="margin:0; padding-left:18px;">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="content-card">
    <div class="card-header" style="display:flex; align-items:center; justify-content:space-between; gap:12px; padding:14px;">
        <h3 style="margin:0;">Assets Register</h3>
        <form method="GET" action="{{ route('admin.hrm.assets') }}" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <input name="q" value="{{ request('q') }}" type="text" placeholder="Search tag, name, serial..." style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; min-width:260px;">
            <select name="status" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">All Status</option>
                @foreach(['available','assigned','maintenance','retired'] as $st)
                    <option value="{{ $st }}" {{ request('status')===$st ? 'selected' : '' }}>{{ strtoupper($st) }}</option>
                @endforeach
            </select>
            <button class="btn-primary" type="submit">Filter</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Tag</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Serial</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th style="width:140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assets as $a)
                <tr>
                    <td style="font-family:var(--mono); font-weight:900;">{{ $a->asset_tag }}</td>
                    <td style="font-weight:900;">{{ $a->name }}</td>
                    <td>{{ $a->category ?: '—' }}</td>
                    <td>{{ $a->serial_number ?: '—' }}</td>
                    <td>
                        <span class="badge" style="background:#f3f4f6; color:#111827; font-weight:900;">{{ strtoupper($a->status) }}</span>
                    </td>
                    <td>{{ $a->assignedEmployee ? ($a->assignedEmployee->first_name . ' ' . $a->assignedEmployee->last_name) : '—' }}</td>
                    <td>
                        <div style="display:flex; gap:8px;">
                            <button type="button" class="btn-ico-circle edit" title="Edit" onclick='openEditModal(@json($a))'>
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <form method="POST" action="{{ route('admin.hrm.assets.destroy', $a) }}" style="display:inline;" onsubmit="return confirm('Delete this asset?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-ico-circle delete" title="Delete">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; padding:20px; color:#6b7280;">No assets found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:16px; padding: 0 14px 14px;">{{ $assets->links() }}</div>
</div>

<div class="modal-overlay" id="assetModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title" id="modalTitle">Add Asset</div>
            <button class="btn-secondary" type="button" onclick="closeModal()">Close</button>
        </div>

        <form id="assetForm" method="POST" action="{{ route('admin.hrm.assets.store') }}">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="modal-body">
                <div class="grid-2">
                    <div class="field">
                        <label>Asset Tag *</label>
                        <input name="asset_tag" id="f_asset_tag" required>
                    </div>
                    <div class="field">
                        <label>Name *</label>
                        <input name="name" id="f_name" required>
                    </div>

                    <div class="field">
                        <label>Category</label>
                        <input name="category" id="f_category">
                    </div>
                    <div class="field">
                        <label>Serial Number</label>
                        <input name="serial_number" id="f_serial_number">
                    </div>

                    <div class="field">
                        <label>Purchase Date</label>
                        <input type="date" name="purchase_date" id="f_purchase_date">
                    </div>
                    <div class="field">
                        <label>Purchase Cost</label>
                        <input type="number" step="0.01" min="0" name="purchase_cost" id="f_purchase_cost" value="0">
                    </div>

                    <div class="field">
                        <label>Condition</label>
                        <select name="condition" id="f_condition">
                            <option value="good">GOOD</option>
                            <option value="fair">FAIR</option>
                            <option value="poor">POOR</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Status</label>
                        <select name="status" id="f_status">
                            <option value="available">AVAILABLE</option>
                            <option value="assigned">ASSIGNED</option>
                            <option value="maintenance">MAINTENANCE</option>
                            <option value="retired">RETIRED</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Assigned Employee</label>
                        <select name="assigned_employee_id" id="f_assigned_employee_id">
                            <option value="">— Not Assigned —</option>
                            @foreach($employees as $e)
                                <option value="{{ $e->id }}">{{ $e->first_name }} {{ $e->last_name }} ({{ $e->employee_number }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Assigned Date</label>
                        <input type="date" name="assigned_date" id="f_assigned_date">
                    </div>

                    <div class="field" style="grid-column: span 2;">
                        <label>Notes</label>
                        <input name="notes" id="f_notes">
                    </div>

                    <div class="field" style="grid-column: span 2;">
                        <label style="display:flex; gap:10px; align-items:center; font-weight:900; color:#111827;">
                            <input type="checkbox" name="is_active" id="f_is_active" value="1" checked>
                            Active
                        </label>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('assetModal');
    const form = document.getElementById('assetForm');
    const methodInput = document.getElementById('formMethod');
    const modalTitle = document.getElementById('modalTitle');

    function openCreateModal() {
        modalTitle.textContent = 'Add Asset';
        form.action = `{{ route('admin.hrm.assets.store') }}`;
        methodInput.value = 'POST';

        document.getElementById('f_asset_tag').value = '';
        document.getElementById('f_name').value = '';
        document.getElementById('f_category').value = '';
        document.getElementById('f_serial_number').value = '';
        document.getElementById('f_purchase_date').value = '';
        document.getElementById('f_purchase_cost').value = '0';
        document.getElementById('f_condition').value = 'good';
        document.getElementById('f_status').value = 'available';
        document.getElementById('f_assigned_employee_id').value = '';
        document.getElementById('f_assigned_date').value = '';
        document.getElementById('f_notes').value = '';
        document.getElementById('f_is_active').checked = true;

        modal.style.display = 'flex';
    }

    function openEditModal(asset) {
        modalTitle.textContent = 'Edit Asset';
        form.action = `/admin/hrm/assets/${asset.id}`;
        methodInput.value = 'PUT';

        document.getElementById('f_asset_tag').value = asset.asset_tag || '';
        document.getElementById('f_name').value = asset.name || '';
        document.getElementById('f_category').value = asset.category || '';
        document.getElementById('f_serial_number').value = asset.serial_number || '';
        document.getElementById('f_purchase_date').value = asset.purchase_date || '';
        document.getElementById('f_purchase_cost').value = asset.purchase_cost || 0;
        document.getElementById('f_condition').value = asset.condition || 'good';
        document.getElementById('f_status').value = asset.status || 'available';
        document.getElementById('f_assigned_employee_id').value = asset.assigned_employee_id || '';
        document.getElementById('f_assigned_date').value = asset.assigned_date || '';
        document.getElementById('f_notes').value = asset.notes || '';
        document.getElementById('f_is_active').checked = !!asset.is_active;

        modal.style.display = 'flex';
    }

    function closeModal() {
        modal.style.display = 'none';
    }

    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    @if($errors->any())
        modal.style.display = 'flex';
    @endif
</script>
@endsection
