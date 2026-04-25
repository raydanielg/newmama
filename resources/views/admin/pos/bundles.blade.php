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

    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(17,24,39,0.55);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        z-index: 9999;
    }
    .modal {
        width: 100%;
        max-width: 980px;
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid rgba(17,24,39,0.1);
        box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    }
    .modal-header {
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
    }
    .modal-title { font-size: 16px; font-weight: 900; }
    .modal-body { padding: 16px; }
    .grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
    .field label { display:block; font-size: 12px; font-weight: 800; color:#6b7280; margin-bottom: 6px; }
    .field input, .field textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
    }
    .modal-footer {
        padding: 16px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        border-top: 1px solid #e5e7eb;
        background: #fff;
    }
    .btn-secondary {
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #fff;
        font-weight: 800;
        cursor: pointer;
    }
    .thumb {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #f3f4f6;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        border: 1px solid #e5e7eb;
    }

    .bundle-items {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #f9fafb;
        padding: 12px;
    }
    .bundle-row {
        display: grid;
        grid-template-columns: 1fr 120px 40px;
        gap: 10px;
        align-items: center;
        margin-bottom: 10px;
    }
    .bundle-row select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #fff;
    }
    .bundle-row input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #fff;
    }
    .mini-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="module-header">
    <div class="header-info">
        <h3>POS Bundles</h3>
        <p>Create packages made of multiple products. Stock will decrement from included products.</p>
    </div>
    <div class="header-actions" style="display:flex; gap:10px;">
        <button class="btn-primary" type="button" onclick="openCreateModal()">Add Bundle</button>
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
        <h3 style="margin:0;">Bundle List</h3>
        <form method="GET" action="{{ route('admin.bundles') }}" style="display:flex; gap:10px; align-items:center;">
            <input name="q" value="{{ request('q') }}" type="text" placeholder="Search name, sku..." style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; min-width:260px;">
            <button class="btn-primary" type="submit">Search</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:60px;">Image</th>
                    <th>Name</th>
                    <th style="width:120px;">SKU</th>
                    <th style="width:120px;">Price</th>
                    <th style="width:90px;">Items</th>
                    <th style="width:110px;">Status</th>
                    <th style="width:140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bundles as $b)
                <tr>
                    <td>
                        <span class="thumb">
                            @if($b->image_url)
                                <img src="{{ $b->image_url }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                            @else
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                            @endif
                        </span>
                    </td>
                    <td style="font-weight:900;">{{ $b->name }}</td>
                    <td>{{ $b->sku }}</td>
                    <td>TSh {{ number_format($b->price, 0) }}</td>
                    <td style="text-align:center;"><span class="badge" style="background:#f3f4f6; color:#111827; font-weight:900;">{{ $b->products_count }}</span></td>
                    <td><span class="badge {{ $b->is_active ? 'status-trying' : 'status-pregnant' }}">{{ $b->is_active ? 'ACTIVE' : 'INACTIVE' }}</span></td>
                    <td>
                        <div style="display:flex; gap:8px;">
                            <button type="button" class="btn-ico-circle edit" title="Edit" onclick="openEditModal({{ $b->id }})">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <form method="POST" action="{{ route('admin.pos.bundles.destroy', $b) }}" style="display:inline;" onsubmit="return confirm('Delete this bundle?');">
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
                <tr><td colspan="7" style="text-align:center; padding:20px; color:#6b7280;">No bundles found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px; padding: 0 14px 14px;">
        {{ $bundles->links() }}
    </div>
</div>

<div class="modal-overlay" id="bundleModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title" id="modalTitle">Add Bundle</div>
            <button class="btn-secondary" type="button" onclick="closeModal()">Close</button>
        </div>

        <form id="bundleForm" method="POST" action="{{ route('admin.pos.bundles.store') }}">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="modal-body">
                <div class="grid-2">
                    <div class="field">
                        <label>SKU *</label>
                        <input name="sku" id="f_sku" required>
                    </div>
                    <div class="field">
                        <label>Price *</label>
                        <input type="number" step="0.01" min="0" name="price" id="f_price" required>
                    </div>

                    <div class="field" style="grid-column: span 2;">
                        <label>Name *</label>
                        <input name="name" id="f_name" required>
                    </div>

                    <div class="field" style="grid-column: span 2;">
                        <label>Description</label>
                        <textarea name="description" id="f_description" rows="3"></textarea>
                    </div>

                    <div class="field">
                        <label>Image URL</label>
                        <input name="image_url" id="f_image_url" placeholder="https://...">
                    </div>
                    <div class="field" style="display:flex; align-items:flex-end;">
                        <label style="display:flex; gap:10px; align-items:center; margin:0; font-size:13px; font-weight:900; color:#111827;">
                            <input type="checkbox" name="is_active" id="f_is_active" value="1" checked>
                            Active
                        </label>
                    </div>

                    <div class="field" style="grid-column: span 2;">
                        <label>Bundle Items (Product + Quantity)</label>
                        <div class="bundle-items" id="bundleItems"></div>
                        <div style="margin-top:10px;">
                            <button type="button" class="btn-secondary" onclick="addItemRow()">Add Item</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-primary">Save Bundle</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('bundleModal');
    const form = document.getElementById('bundleForm');
    const methodInput = document.getElementById('formMethod');
    const modalTitle = document.getElementById('modalTitle');
    const itemsWrap = document.getElementById('bundleItems');

    const PRODUCTS = {!! json_encode($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name])) !!};
    const BUNDLES = {!! json_encode($bundles->map(fn($b) => ['id' => $b->id, 'sku' => $b->sku, 'name' => $b->name, 'description' => $b->description, 'price' => (string)$b->price, 'image_url' => $b->image_url, 'is_active' => $b->is_active])) !!};

    function productOptions(selectedId) {
        return PRODUCTS.map(p => `<option value="${p.id}" ${String(selectedId) === String(p.id) ? 'selected' : ''}>${p.name}</option>`).join('');
    }

    function addItemRow(pid = '', qty = '') {
        const row = document.createElement('div');
        row.className = 'bundle-row';
        row.innerHTML = `
            <select name="product_ids[]">
                <option value="">— Select Product —</option>
                ${productOptions(pid)}
            </select>
            <input type="number" step="0.01" min="0.01" name="product_qtys[]" placeholder="Qty" value="${qty}">
            <button type="button" class="mini-btn" onclick="this.parentElement.remove()">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        `;
        itemsWrap.appendChild(row);
    }

    function openCreateModal() {
        modalTitle.textContent = 'Add Bundle';
        form.action = `{{ route('admin.pos.bundles.store') }}`;
        methodInput.value = 'POST';

        document.getElementById('f_sku').value = '';
        document.getElementById('f_name').value = '';
        document.getElementById('f_description').value = '';
        document.getElementById('f_price').value = '';
        document.getElementById('f_image_url').value = '';
        document.getElementById('f_is_active').checked = true;

        itemsWrap.innerHTML = '';
        addItemRow();

        modal.style.display = 'flex';
    }

    async function openEditModal(id) {
        modalTitle.textContent = 'Edit Bundle';
        form.action = `/admin/pos/bundles/${id}`;
        methodInput.value = 'PUT';

        const b = BUNDLES.find(x => String(x.id) === String(id));
        if (!b) return;

        document.getElementById('f_sku').value = b.sku || '';
        document.getElementById('f_name').value = b.name || '';
        document.getElementById('f_description').value = b.description || '';
        document.getElementById('f_price').value = b.price || '';
        document.getElementById('f_image_url').value = b.image_url || '';
        document.getElementById('f_is_active').checked = !!b.is_active;

        itemsWrap.innerHTML = '';

        try {
            const resp = await fetch(`/admin/pos/bundles/${id}/items`);
            const json = await resp.json();
            if (json.success) {
                (json.items || []).forEach(it => addItemRow(it.product_id, it.quantity));
                if ((json.items || []).length === 0) addItemRow();
            } else {
                addItemRow();
            }
        } catch (e) {
            addItemRow();
        }

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
