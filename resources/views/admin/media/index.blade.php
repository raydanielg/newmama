@extends('layouts.admin')

@section('title', 'Media Manager')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Media Manager</h3>
        <p>Upload and manage images for your products.</p>
    </div>
    <div class="header-actions">
        <button class="btn-primary" onclick="openUploadModal()">Upload Files</button>
    </div>
</div>

<style>
    .media-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 20px; padding: 20px; }
    .media-item { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: transform 0.2s; cursor: pointer; position: relative; }
    .media-item:hover { transform: translateY(-5px); }
    .media-thumb { width: 100%; aspect-ratio: 1; object-fit: cover; background: #eee; }
    .media-info { padding: 10px; font-size: 12px; background: white; }
    .media-name { font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--malkia-dark); }
    .media-actions { position: absolute; top: 5px; right: 5px; display: flex; gap: 5px; opacity: 0; transition: opacity 0.2s; z-index: 5; }
    .media-item:hover .media-actions { opacity: 1; }
    .btn-delete { background: rgba(239, 68, 68, 0.9); color: white; border: none; border-radius: 4px; padding: 4px; cursor: pointer; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; }
    
    /* Fix for faint/dark overlay issue */
    .modal-overlay { 
        position: fixed; 
        inset: 0; 
        background: rgba(0, 0, 0, 0.7); 
        display: none; 
        align-items: center; 
        justify-content: center; 
        z-index: 99999; 
    }
    .modal-overlay.active {
        display: flex;
    }
    .media-modal { 
        background: white; 
        padding: 30px; 
        border-radius: 15px; 
        width: 100%;
        max-width: 450px; 
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        position: relative;
        z-index: 100000;
    }
</style>

<div class="content-card">
    <div class="media-grid" id="mediaGrid">
        @foreach($media as $item)
            <div class="media-item" onclick="copyUrl('{{ $item->url }}')">
                <img src="{{ $item->url }}" class="media-thumb" alt="{{ $item->original_name }}">
                <div class="media-info">
                    <div class="media-name">{{ $item->original_name }}</div>
                </div>
                <div class="media-actions">
                    <button class="btn-delete" onclick="event.stopPropagation(); deleteMedia({{ $item->id }})">&times;</button>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Upload Modal --}}
<div class="modal-overlay" id="uploadModal">
    <div class="media-modal">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0">Upload Images</h4>
            <button type="button" class="btn-close" onclick="closeUploadModal()"></button>
        </div>
        
        <div class="mb-4">
            <label class="form-label fw-bold">Select from Computer</label>
            <input type="file" id="mediaFiles" multiple accept="image/*" class="form-control form-control-lg border-primary">
            <small class="text-muted mt-2 d-block">You can select multiple images.</small>
        </div>

        <div id="uploadProgress" style="display:none;" class="mb-3">
            <div class="progress" style="height:10px; border-radius: 10px;">
                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width:0%"></div>
            </div>
            <small id="uploadStatus" class="text-center d-block mt-2 fw-bold">Uploading...</small>
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="button" id="startUploadBtn" class="btn btn-primary btn-lg fw-bold shadow" onclick="startUpload()">
                Start Upload
            </button>
            <button type="button" class="btn btn-light" onclick="closeUploadModal()">Cancel</button>
        </div>
    </div>
</div>

<script>
    function openUploadModal() { 
        document.getElementById('uploadModal').classList.add('active'); 
    }
    function closeUploadModal() { 
        document.getElementById('uploadModal').classList.remove('active'); 
        document.getElementById('mediaFiles').value = '';
        document.getElementById('uploadProgress').style.display = 'none';
    }

    async function startUpload() {
        const files = document.getElementById('mediaFiles').files;
        if (files.length === 0) return;

        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }

        const progressDiv = document.getElementById('uploadProgress');
        const progressBar = document.getElementById('progressBar');
        const statusText = document.getElementById('uploadStatus');
        const uploadBtn = document.getElementById('startUploadBtn');

        progressDiv.style.display = 'block';
        uploadBtn.disabled = true;
        progressBar.style.width = '0%';

        try {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route('admin.media.store') }}', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percent + '%';
                    statusText.innerText = `Uploading: ${percent}%`;
                }
            };

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        statusText.innerText = 'Upload Complete!';
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    }
                } else {
                    alert('Upload failed');
                    progressDiv.style.display = 'none';
                    uploadBtn.disabled = false;
                }
            };

            xhr.send(formData);
        } catch (e) {
            alert('An error occurred');
            progressDiv.style.display = 'none';
            uploadBtn.disabled = false;
        }
    }

    async function deleteMedia(id) {
        if (!confirm('Are you sure?')) return;
        const response = await fetch(`/admin/media/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        if (response.ok) location.reload();
    }

    function copyUrl(url) {
        navigator.clipboard.writeText(url);
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'URL copied!',
            showConfirmButton: false,
            timer: 2000
        });
    }
</script>
@endsection
