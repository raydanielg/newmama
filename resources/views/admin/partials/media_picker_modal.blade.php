{{-- Media Picker Modal --}}
<div class="modal-overlay" id="mediaPickerModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:10000; align-items:center; justify-content:center;">
    <div class="modal" style="background:white; width:95%; max-width:950px; height:90vh; border-radius:15px; display:flex; flex-direction:column; overflow:hidden; position: relative; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
        <div class="modal-header" style="padding:15px 20px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center; background: #f8f9fa; position: relative; z-index: 10;">
            <h5 class="fw-bold m-0 text-dark">Select Product Image</h5>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary fw-bold px-4" onclick="openMediaUpload()">+ Upload New</button>
                <button type="button" class="btn-close" onclick="closeMediaPicker()"></button>
            </div>
        </div>
        <div class="modal-body" style="flex-grow:1; overflow-y:auto; padding:20px; background: white;">
            <div id="mediaPickerGrid" class="row g-3">
                <!-- Media items loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

{{-- Inner Upload Modal --}}
<div class="modal-overlay" id="mediaPickerUploadModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:10001; align-items:center; justify-content:center;">
    <div class="modal" style="background:white; padding:30px; border-radius:15px; width:450px; box-shadow:0 30px 60px rgba(0,0,0,0.3); position: relative; z-index: 10002;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold m-0">Upload Computer Storage</h5>
            <button type="button" class="btn-close" onclick="closeMediaUpload()"></button>
        </div>
        
        <div class="mb-4">
            <label class="form-label fw-bold">Select Images from Computer</label>
            <input type="file" id="mediaPickerFiles" multiple accept="image/*" class="form-control form-control-lg border-primary">
            <div style="font-size: 13px; color: #6c757d; mt-2;">Unaruhusiwa kuchagua picha nyingi kwa mara moja.</div>
        </div>
        
        <div id="mediaPickerFileInfo" style="margin-top: 15px; padding: 12px; background: #e8f5e9; border-left: 4px solid #2e7d32; border-radius: 4px; font-size: 14px; color: #2e7d32; font-weight: 700; display: none;"></div>

        <div id="mediaPickerProgress" style="display:none; margin-top: 20px;">
            <div class="progress" style="height:10px; border-radius:10px; background: #e9ecef;">
                <div id="mediaPickerProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width:0%"></div>
            </div>
            <small id="mediaPickerStatus" class="text-dark fw-bold mt-2 d-block text-center">Inatuma picha...</small>
        </div>
        
        <div class="d-grid gap-2 mt-4">
            <button type="button" id="mediaStartUploadBtn" class="btn btn-success btn-lg fw-bold shadow" onclick="startMediaPickerUpload()" disabled>
                Anza Kupakia (Start Upload)
            </button>
            <button type="button" class="btn btn-light" onclick="closeMediaUpload()">Ghairi (Cancel)</button>
        </div>
    </div>
</div>

<script>
    let onMediaSelected = null;

    window.openMediaPicker = function(callback) {
        onMediaSelected = callback;
        document.getElementById('mediaPickerModal').style.display = 'flex';
        loadMediaItems();
    };

    function closeMediaPicker() {
        document.getElementById('mediaPickerModal').style.display = 'none';
    }

    function openMediaUpload() {
        document.getElementById('mediaPickerUploadModal').style.display = 'flex';
    }

    function closeMediaUpload() {
        document.getElementById('mediaPickerUploadModal').style.display = 'none';
        document.getElementById('mediaPickerFiles').value = '';
        document.getElementById('mediaPickerProgress').style.display = 'none';
        document.getElementById('mediaPickerFileInfo').style.display = 'none';
        document.getElementById('mediaStartUploadBtn').disabled = true;
    }

    // Handle file selection in picker
    document.getElementById('mediaPickerFiles').addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            const info = document.getElementById('mediaPickerFileInfo');
            info.textContent = `✅ ${this.files.length} file(s) selected ready to upload`;
            info.style.display = 'block';
            document.getElementById('mediaStartUploadBtn').disabled = false;
        }
    });

    async function loadMediaItems() {
        const grid = document.getElementById('mediaPickerGrid');
        grid.innerHTML = '<div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div></div>';
        
        try {
            const response = await fetch('{{ route('admin.media.index') }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            
            grid.innerHTML = '';
            data.media.forEach(item => {
                const col = document.createElement('div');
                col.className = 'col-6 col-md-3 col-lg-2';
                col.innerHTML = `
                    <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden" style="cursor:pointer;" onclick="selectMediaItem('${item.url}')">
                        <img src="${item.url}" class="card-img-top" style="aspect-ratio:1; object-fit:cover;">
                        <div class="p-2 text-center">
                            <small class="text-truncate d-block fw-bold" style="font-size:10px;">${item.original_name}</small>
                        </div>
                    </div>
                `;
                grid.appendChild(col);
            });

            if (data.media.length === 0) {
                grid.innerHTML = '<div class="col-12 text-center py-5 text-muted">No images found. Upload some!</div>';
            }
        } catch (e) {
            grid.innerHTML = '<div class="col-12 text-center py-5 text-danger">Failed to load media.</div>';
        }
    }

    function selectMediaItem(url) {
        if (onMediaSelected) {
            onMediaSelected(url);
        }
        closeMediaPicker();
    }

    async function startMediaPickerUpload() {
        const files = document.getElementById('mediaPickerFiles').files;
        if (files.length === 0) return;

        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }

        const progressDiv = document.getElementById('mediaPickerProgress');
        const progressBar = document.getElementById('mediaPickerProgressBar');
        const statusText = document.getElementById('mediaPickerStatus');
        const uploadBtn = document.getElementById('mediaStartUploadBtn');
        
        progressDiv.style.display = 'block';
        uploadBtn.disabled = true;
        progressBar.style.width = '0%';
        statusText.innerText = 'Uploading...';

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
                            closeMediaUpload();
                            loadMediaItems();
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
            alert('An error occurred during upload');
            progressDiv.style.display = 'none';
            uploadBtn.disabled = false;
        }
    }
</script>
