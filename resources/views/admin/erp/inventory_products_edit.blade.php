@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Edit Product</h3>
        <p>Update product details, barcode, prices, and stock.</p>
    </div>
    <div class="header-actions" style="display:flex; gap:10px;">
        <a href="{{ route('admin.inventory.products') }}" class="btn-icon" style="text-decoration:none;">Back</a>
    </div>
</div>

<style>
    .img-row { display:flex; gap:12px; align-items:center; }
    .img-preview { width:72px; height:72px; border-radius:12px; border:1px solid #e5e7eb; background:#f3f4f6; overflow:hidden; display:flex; align-items:center; justify-content:center; color:#9ca3af; }
    .img-preview img { width:100%; height:100%; object-fit:cover; }
    .btn-secondary { padding:10px 14px; border-radius:10px; border:1px solid #e5e7eb; background:#fff; font-weight:800; cursor:pointer; }
</style>

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

<div class="content-card" style="padding:16px;">
    <form method="POST" action="{{ route('admin.inventory.products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap:14px;">
            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:900; margin-bottom:6px;">Name *</label>
                <input name="name" value="{{ old('name', $product->name) }}" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>

            <div>
                <label style="display:block; font-weight:900; margin-bottom:6px;">SKU *</label>
                <input name="sku" value="{{ old('sku', $product->sku) }}" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>
            <div>
                <label style="display:block; font-weight:900; margin-bottom:6px;">Barcode (optional)</label>
                <input name="barcode" value="{{ old('barcode', $product->barcode) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <div style="font-size:12px; color:#6b7280; margin-top:6px;">Leave blank to auto-generate from SKU.</div>
            </div>

            <div>
                <label style="display:block; font-weight:900; margin-bottom:6px;">Category</label>
                <input name="category" value="{{ old('category', $product->category) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>
            <div>
                <label style="display:block; font-weight:900; margin-bottom:6px;">Image URL</label>
                <input name="image_url" value="{{ old('image_url', $product->image_url) }}" placeholder="https://..." style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <div style="margin-top:10px;" class="img-row">
                    <div class="img-preview" id="imgPreview">
                        @if(old('image_url', $product->image_url))
                            <img src="{{ old('image_url', $product->image_url) }}" alt="">
                        @else
                            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        @endif
                    </div>
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        <button type="button" class="btn btn-primary fw-bold" onclick="openMediaPicker()">Select Image (Media)</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="openDirectUpload()">Upload from Computer</button>
                        <div style="font-size:12px; color:#6b7280;">Or paste Image URL above.</div>
                    </div>
                </div>
            </div>

            {{-- Direct Upload Section (Matching Media Manager Style) --}}
            <div id="directUploadSection" style="display:none; grid-column: span 2; margin-top: 10px; background: #f8f9fa; padding: 20px; border-radius: 15px; border: 1px solid #eee;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold m-0">Upload from Computer Storage</h6>
                    <button type="button" class="btn-close" onclick="closeDirectUpload()"></button>
                </div>
                <div id="direct-drop-zone" style="border: 3px dashed #dee2e6; border-radius: 15px; padding: 30px 20px; text-align: center; cursor: pointer; position: relative; transition: all 0.3s ease; background: #fff;">
                    <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="#6c757d" stroke-width="2" style="margin-bottom: 10px;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    <div style="font-weight: 800; color: #495057; font-size: 14px; margin-bottom: 5px;">Gusa Hapa ili Kuchagua Picha</div>
                    <input type="file" id="directFile" accept="image/*" 
                           style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 100;">
                </div>
                <div id="directFileInfo" style="margin-top: 15px; padding: 10px; background: #e8f5e9; border-radius: 10px; font-size: 13px; color: #2e7d32; font-weight: 700; display: none;"></div>
                <div id="directProgress" style="display:none; margin-top: 15px;">
                    <div class="progress" style="height:8px; border-radius:10px;">
                        <div id="directProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width:0%"></div>
                    </div>
                </div>
                <button type="button" id="directStartUploadBtn" class="btn btn-success w-100 mt-3 fw-bold shadow" onclick="startDirectUpload()" disabled>Upload Now</button>
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:900; margin-bottom:6px;">Description</label>
                <textarea name="description" rows="3" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">{{ old('description', $product->description) }}</textarea>
            </div>

            <div>
                <label style="display:block; font-weight:900; margin-bottom:6px;">Cost Price *</label>
                <input type="number" step="0.01" min="0" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>
            <div>
                <label style="display:block; font-weight:900; margin-bottom:6px;">Selling Price *</label>
                <input type="number" step="0.01" min="0" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>

            <div>
                <label style="display:block; font-weight:900; margin-bottom:6px;">Qty On Hand *</label>
                <input type="number" step="0.01" min="0" name="qty_on_hand" value="{{ old('qty_on_hand', $product->qty_on_hand) }}" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>
            <div style="display:flex; align-items:flex-end;">
                <label style="display:flex; gap:10px; align-items:center; font-weight:900;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                    Active
                </label>
            </div>
        </div>

        <div style="margin-top:18px; display:flex; gap:10px;">
            <button type="submit" class="btn-primary">Save Changes</button>
            <a href="{{ route('admin.inventory.products') }}" class="btn-icon" style="text-decoration:none;">Cancel</a>
        </div>
    </form>
</div>

@include('admin.partials.media_picker_modal')

<script>
    const imgPreview = document.getElementById('imgPreview');
    const imageUrlInput = document.querySelector('input[name="image_url"]');

    function openMediaPicker() {
        window.openMediaPicker(function(url) {
            imageUrlInput.value = url;
            imgPreview.innerHTML = `<img src="${url}" alt="" style="width:100%; height:100%; object-fit:cover;">`;
        });
    }

    // Direct Upload Handlers
    function openDirectUpload() { document.getElementById('directUploadSection').style.display = 'block'; }
    function closeDirectUpload() { 
        document.getElementById('directUploadSection').style.display = 'none';
        document.getElementById('directFile').value = '';
        document.getElementById('directFileInfo').style.display = 'none';
    }

    document.getElementById('directFile').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const info = document.getElementById('directFileInfo');
            info.textContent = `Selected: ${this.files[0].name}`;
            info.style.display = 'block';
            document.getElementById('directStartUploadBtn').disabled = false;
        }
    });

    async function startDirectUpload() {
        const file = document.getElementById('directFile').files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('files[]', file);

        const progressDiv = document.getElementById('directProgress');
        const progressBar = document.getElementById('directProgressBar');
        const uploadBtn = document.getElementById('directStartUploadBtn');
        
        progressDiv.style.display = 'block';
        uploadBtn.disabled = true;

        try {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route('admin.media.store') }}', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percent + '%';
                }
            };

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success && response.media.length > 0) {
                        const url = response.media[0].url;
                        imageUrlInput.value = url;
                        imgPreview.innerHTML = `<img src="${url}" alt="" style="width:100%; height:100%; object-fit:cover;">`;
                        closeDirectUpload();
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Uploaded!', showConfirmButton: false, timer: 2000 });
                    }
                } else {
                    alert('Upload failed');
                    progressDiv.style.display = 'none';
                    uploadBtn.disabled = false;
                }
            };
            xhr.send(formData);
        } catch (e) {
            alert('Error occurred');
            progressDiv.style.display = 'none';
            uploadBtn.disabled = false;
        }
    }
</script>
@endsection
