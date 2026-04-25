@extends('layouts.admin')
@section('title', 'Services Management')
@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Services</h3>
        <p>Manage services offered by Malkia Konnect.</p>
    </div>
    <div class="header-actions">
        <button class="btn-primary" onclick="document.getElementById('addServiceModal').style.display='flex'">Add Service</button>
    </div>
</div>
<div class="content-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price (TSh)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $s)
                <tr>
                    <td style="font-weight:900;">{{ $s->name }}</td>
                    <td>{{ $s->category }}</td>
                    <td>{{ number_format($s->base_price, 0) }}</td>
                    <td><span class="badge {{ $s->is_active ? 'badge-success' : 'badge-danger' }}">{{ $s->is_active ? 'ACTIVE' : 'INACTIVE' }}</span></td>
                    <td>
                        <button class="btn-icon" onclick='openEditModal(@json($s))'>Edit</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:10px;">{{ $services->links() }}</div>
</div>

<div id="addServiceModal" class="modal-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:9999;">
    <div class="modal" style="background:#fff; padding:20px; border-radius:10px; width:400px;">
        <h3 id="modalTitle">Add Service</h3>
        <form id="serviceForm" method="POST" action="{{ route('admin.services.store') }}">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div style="margin-bottom:10px;">
                <label style="display:block;">Name</label>
                <input type="text" name="name" id="f_name" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;">
            </div>
            <div style="margin-bottom:10px;">
                <label style="display:block;">Category</label>
                <input type="text" name="category" id="f_category" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;">
            </div>
            <div style="margin-bottom:10px;">
                <label style="display:block;">Price</label>
                <input type="number" name="base_price" id="f_price" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;">
            </div>
            <div style="margin-bottom:10px;">
                <label style="display:block;">Description</label>
                <textarea name="description" id="f_desc" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;"></textarea>
            </div>
            <div style="margin-bottom:10px;">
                <label style="display:flex; gap:10px; align-items:center;">
                    <input type="checkbox" name="is_active" id="f_active" value="1" checked> Active
                </label>
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" onclick="document.getElementById('addServiceModal').style.display='none'" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(s) {
    document.getElementById('modalTitle').textContent = 'Edit Service';
    document.getElementById('serviceForm').action = '/admin/services/' + s.id;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('f_name').value = s.name;
    document.getElementById('f_category').value = s.category;
    document.getElementById('f_price').value = s.base_price;
    document.getElementById('f_desc').value = s.description;
    document.getElementById('f_active').checked = s.is_active;
    document.getElementById('addServiceModal').style.display = 'flex';
}
</script>
@endsection
