@extends('layouts.admin')

@section('title', 'Mothers Intake')

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
    }
    .btn-ico-circle:hover { background: #e5e7eb; color: #111827; }
    .btn-ico-circle.edit:hover { background: #dbeafe; color: #2563eb; border-color: #bfdbfe; }
    .btn-ico-circle.msg:hover { background: #fef3c7; color: #d97706; border-color: #fde68a; }
    .btn-ico-circle.approve:hover { background: #dcfce7; color: #16a34a; border-color: #bbf7d0; }
    .btn-ico-circle.delete { cursor: pointer; }
    .btn-ico-circle.delete:hover { background: #fee2e2; color: #dc2626; border-color: #fecaca; }
    
    .count-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #ef4444;
        color: white;
        font-size: 10px;
        font-weight: 800;
        padding: 2px 5px;
        border-radius: 10px;
        border: 2px solid white;
        line-height: 1;
    }
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
    }
    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        padding: 0;
        border-radius: 16px;
        width: 100%;
        max-width: 600px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        position: relative;
        overflow: hidden;
    }
    .modal-header {
        padding: 20px 24px;
        background: #f9fafb;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h3 { margin: 0; font-size: 18px; font-weight: 800; color: #111827; }
    .close-modal { cursor: pointer; color: #9ca3af; transition: color 0.2s; }
    .close-modal:hover { color: #111827; }
    .modal-body { padding: 24px; }
    .modal-footer {
        padding: 16px 24px;
        background: #f9fafb;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .form-group-full { grid-column: span 2; }
    .modal-input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        font-size: 14px;
        transition: border-color 0.2s;
    }
    .modal-input:focus {
        outline: none;
        border-color: #4c1d95;
        box-shadow: 0 0 0 3px rgba(76, 29, 149, 0.1);
    }
    .loading-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0.7);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }
</style>
<div class="module-header">
    <div class="header-info">
        <h3>Mothers Database</h3>
        <p>List of all mothers registered via the Join Konnect form.</p>
    </div>
    <div class="header-actions" style="display:flex; gap:10px;">
        <button class="btn-primary" onclick="previewPrint()" style="border:none; cursor:pointer; background:#4c1d95;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:5px; vertical-align:middle;"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v8H6z"/></svg>
            Preview & Print
        </button>
        <button class="btn-primary" onclick="openAddMotherModal()" style="border:none; cursor:pointer;">Add Mother</button>
        <a href="{{ route('admin.mothers.import') }}" class="btn-primary" style="text-decoration:none;">Import</a>
    </div>
</div>

@if(session('status'))
    <div class="content-card" style="padding:12px; border:1px solid #bbf7d0; background:#f0fdf4; color:#166534; margin-bottom:14px;">{{ session('status') }}</div>
@endif

@if(session('error'))
    <div class="content-card" style="padding:12px; border:1px solid #fecaca; background:#fff1f2; color:#991b1b; margin-bottom:14px;">{{ session('error') }}</div>
@endif

<div class="content-card" style="margin-bottom:16px;">
    <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:center; justify-content:space-between; padding: 14px;">
        <form id="filter-form" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name, WhatsApp, MK#..." style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; min-width:240px;">
            <select name="status" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">All Status</option>
                @foreach(['pregnant' => 'Pregnant', 'new_parent' => 'New Parent', 'trying' => 'Trying'] as $k => $v)
                    <option value="{{ $k }}" {{ request('status') === $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
            <select name="approved" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">All Approval</option>
                <option value="1" {{ request('approved') === '1' ? 'selected' : '' }}>Approved</option>
                <option value="0" {{ request('approved') === '0' ? 'selected' : '' }}>Pending</option>
            </select>
            <select name="per_page" id="per_page" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>Show 10</option>
                <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>Show 15</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>Show 100</option>
                <option value="500" {{ request('per_page') == 500 ? 'selected' : '' }}>Show 500</option>
                <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Show All</option>
            </select>
            <button class="btn-primary" type="submit">Filter</button>
        </form>

        <div style="font-size:12px; color:#6b7280;">Total: <strong id="total-count">{{ $mothers instanceof \Illuminate\Pagination\LengthAwarePaginator ? number_format($mothers->total()) : number_format(count($mothers)) }}</strong></div>
    </div>
</div>

<div id="table-container">
    @include('admin.mothers.partials.table', ['mothers' => $mothers])
</div>

<!-- Add Mother Modal -->
<div id="addMotherModal" class="modal">
    <div class="modal-content">
        <div class="loading-overlay" id="modal-loading">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
        <div class="modal-header">
            <h3>Add New Mother</h3>
            <span class="close-modal" onclick="closeAddMotherModal()">&times;</span>
        </div>
        <form id="add-mother-form">
            @csrf
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group form-group-full">
                        <label style="font-size:12px; font-weight:700; color:#374151; margin-bottom:4px; display:block;">Full Name *</label>
                        <input type="text" name="full_name" class="modal-input" required placeholder="Amina Hassan">
                    </div>
                    <div class="form-group">
                        <label style="font-size:12px; font-weight:700; color:#374151; margin-bottom:4px; display:block;">WhatsApp Number *</label>
                        <input type="text" name="whatsapp_number" class="modal-input" required placeholder="07XXXXXXXX">
                    </div>
                    <div class="form-group">
                        <label style="font-size:12px; font-weight:700; color:#374151; margin-bottom:4px; display:block;">Journey Status *</label>
                        <select name="status" id="modal-status" class="modal-input" required onchange="toggleModalFields()">
                            <option value="pregnant">Pregnant</option>
                            <option value="new_parent">New Parent</option>
                            <option value="trying">Trying</option>
                        </select>
                    </div>
                    
                    <!-- Dynamic Fields -->
                    <div class="form-group" id="modal-edd-group">
                        <label style="font-size:12px; font-weight:700; color:#374151; margin-bottom:4px; display:block;">EDD Date *</label>
                        <input type="date" name="edd_date" class="modal-input">
                    </div>
                    <div class="form-group" id="modal-age-group" style="display:none;">
                        <label style="font-size:12px; font-weight:700; color:#374151; margin-bottom:4px; display:block;">Baby Age (Months) *</label>
                        <input type="number" name="baby_age" class="modal-input" min="0" max="24">
                    </div>
                    <div class="form-group form-group-full" id="modal-trying-group" style="display:none;">
                        <label style="font-size:12px; font-weight:700; color:#374151; margin-bottom:4px; display:block;">Trying Duration *</label>
                        <input type="text" name="trying_duration" class="modal-input" placeholder="e.g. 6 months">
                    </div>

                    <div class="form-group">
                        <label style="font-size:12px; font-weight:700; color:#374151; margin-bottom:4px; display:block;">Region *</label>
                        <select name="region_id" id="modal-region" class="modal-input" required onchange="loadModalDistricts()">
                            <option value="" disabled selected>Select Region</option>
                            @foreach(App\Models\Region::orderBy('name')->get() as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="font-size:12px; font-weight:700; color:#374151; margin-bottom:4px; display:block;">District *</label>
                        <select name="district_id" id="modal-district" class="modal-input" required disabled>
                            <option value="" disabled selected>Select District</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeAddMotherModal()" style="border:none; cursor:pointer; padding:10px 16px; border-radius:10px;">Cancel</button>
                <button type="submit" class="btn-primary" style="border:none; cursor:pointer;">Save & Approve</button>
            </div>
        </form>
    </div>
</div>

<script>
    const filterForm = document.getElementById('filter-form');
    const tableContainer = document.getElementById('table-container');

    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        updateTable();
    });

    function updateTable(url = null) {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();
        
        for (const [key, value] of formData.entries()) {
            if (value !== '') {
                params.append(key, value);
            }
        }

        const fetchUrl = url || `{{ route('admin.mothers') }}?${params.toString()}`;

        tableContainer.style.opacity = '0.5';
        
        fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            tableContainer.innerHTML = html;
            tableContainer.style.opacity = '1';
            
            // Re-bind pagination links
            bindPagination();
        })
        .catch(error => {
            console.error('Error updating table:', error);
            tableContainer.style.opacity = '1';
        });
    }

    function previewPrint() {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();
        
        for (const [key, value] of formData.entries()) {
            if (value !== '') {
                params.append(key, value);
            }
        }
        params.append('preview', '1');

        window.open(`{{ route('admin.mothers') }}?${params.toString()}`, '_blank');
    }

    function bindPagination() {
        const links = tableContainer.querySelectorAll('#pagination-links a');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                updateTable(this.href);
            });
        });
    }

    // Modal Logic
    const modal = document.getElementById('addMotherModal');
    const addForm = document.getElementById('add-mother-form');
    const loading = document.getElementById('modal-loading');

    function openAddMotherModal() {
        modal.style.display = 'block';
    }

    function closeAddMotherModal() {
        modal.style.display = 'none';
        addForm.reset();
        toggleModalFields();
    }

    window.onclick = function(event) {
        if (event.target == modal) closeAddMotherModal();
    }

    function toggleModalFields() {
        const status = document.getElementById('modal-status').value;
        const eddGroup = document.getElementById('modal-edd-group');
        const ageGroup = document.getElementById('modal-age-group');
        const tryingGroup = document.getElementById('modal-trying-group');

        eddGroup.style.display = status === 'pregnant' ? 'block' : 'none';
        ageGroup.style.display = status === 'new_parent' ? 'block' : 'none';
        tryingGroup.style.display = status === 'trying' ? 'block' : 'none';

        // Set required attributes
        eddGroup.querySelector('input').required = status === 'pregnant';
        ageGroup.querySelector('input').required = status === 'new_parent';
        tryingGroup.querySelector('input').required = status === 'trying';
    }

    async function loadModalDistricts() {
        const regionId = document.getElementById('modal-region').value;
        const districtSelect = document.getElementById('modal-district');
        
        districtSelect.disabled = true;
        districtSelect.innerHTML = '<option value="" disabled selected>Loading...</option>';

        try {
            const response = await fetch(`/api/regions/${regionId}/districts`);
            const districts = await response.json();
            
            districtSelect.innerHTML = '<option value="" disabled selected>Select District</option>';
            districts.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.id;
                opt.textContent = d.name;
                districtSelect.appendChild(opt);
            });
            districtSelect.disabled = false;
        } catch (error) {
            districtSelect.innerHTML = '<option value="" disabled selected>Error loading</option>';
        }
    }

    addForm.addEventListener('submit', function(e) {
        e.preventDefault();
        loading.style.display = 'flex';

        const formData = new FormData(this);

        fetch(`{{ route('admin.mothers.store') }}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            loading.style.display = 'none';
            if (data.success) {
                closeAddMotherModal();
                updateTable(); // Refresh the table
                alert(data.message);
            } else {
                alert(data.message || 'Error occurred');
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            alert('Something went wrong. Check console.');
            console.error(error);
        });
    });

    // Initial binding
    bindPagination();
</script>
@endsection
