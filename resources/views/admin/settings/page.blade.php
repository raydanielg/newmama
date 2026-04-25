@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Update configuration values. Saved values are stored in the database.</p>
    </div>
</div>

<div class="content-card" style="padding:16px;">
    <form method="POST" action="{{ route('admin.settings.update', ['page' => $page]) }}" style="display:grid; grid-template-columns: 1fr 1fr; gap:12px; align-items:end;">
        @csrf

        @foreach($fields as $f)
            <div style="grid-column: 1 / -1;">
                <label class="form-label">{{ $f['label'] }}</label>
                <input
                    type="{{ $f['type'] ?? 'text' }}"
                    name="{{ $f['key'] }}"
                    value="{{ old($f['key'], $values[$f['key']] ?? '') }}"
                    style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;"
                >
            </div>
        @endforeach

        <div style="grid-column: 1 / -1; display:flex; justify-content:flex-end;">
            <button class="btn-primary" type="submit">Save Settings</button>
        </div>
    </form>
</div>
@endsection
