@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Import Mothers</h3>
        <p>Upload CSV, preview, then confirm import.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.mothers') }}" class="btn-primary" style="text-decoration:none;">Back</a>
    </div>
</div>

@if(session('error'))
    <div class="content-card" style="padding:12px; border:1px solid #fecaca; background:#fff1f2; color:#991b1b; margin-bottom:14px;">{{ session('error') }}</div>
@endif

<div class="content-card" style="padding:16px;">
    <div style="font-size:12px; color:#6b7280; margin-bottom:12px;">
        CSV columns required:
        <strong>full_name</strong>, <strong>whatsapp_number</strong>, <strong>status</strong>
        <br>
        Recommended:
        <strong>region_id</strong>, <strong>district_id</strong>, <strong>edd_date</strong>, <strong>baby_age</strong>, <strong>trying_duration</strong>, <strong>country</strong> (name or ISO2)
    </div>

    <form method="POST" action="{{ route('admin.mothers.import.preview') }}" enctype="multipart/form-data" style="display:flex; gap:12px; align-items:end; flex-wrap:wrap;">
        @csrf
        <div>
            <label style="display:block; font-weight:800; margin-bottom:6px;">CSV File</label>
            <input type="file" name="file" accept=".csv,text/csv" required>
            @error('file')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
        </div>
        <button class="btn-primary" type="submit">Preview Import</button>
    </form>
</div>
@endsection
