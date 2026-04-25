@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Import Preview</h3>
        <p>Review rows and confirm import. Invalid rows will be skipped.</p>
    </div>
    <div class="header-actions" style="display:flex; gap:10px;">
        <a href="{{ route('admin.mothers.import') }}" class="btn-primary" style="text-decoration:none;">Back</a>
        <form method="POST" action="{{ route('admin.mothers.import.confirm') }}" style="display:inline;">
            @csrf
            <button class="btn-primary" type="submit">Confirm Import</button>
        </form>
    </div>
</div>

@if($errors && count($errors) > 0)
    <div class="content-card" style="padding:12px; border:1px solid #fecaca; background:#fff1f2; color:#991b1b; margin-bottom:14px;">
        <div style="font-weight:900; margin-bottom:8px;">Issues detected</div>
        <ul style="margin:0; padding-left:18px;">
            @foreach(array_slice($errors, 0, 10) as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
        @if(count($errors) > 10)
            <div style="margin-top:8px; font-size:12px;">And {{ count($errors) - 10 }} more…</div>
        @endif
    </div>
@endif

<div class="content-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:70px;">Row</th>
                    <th>Name</th>
                    <th>WhatsApp</th>
                    <th style="width:120px;">Status</th>
                    <th style="width:120px;">Region</th>
                    <th style="width:120px;">District</th>
                    <th style="width:130px;">EDD</th>
                    <th style="width:100px;">Baby Age</th>
                    <th>Errors</th>
                </tr>
            </thead>
            <tbody>
                @foreach($preview as $r)
                    <tr style="{{ !empty($r['_errors']) ? 'background:#fff7ed;' : '' }}">
                        <td style="font-family:var(--mono);">{{ $r['_row'] }}</td>
                        <td style="font-weight:800;">{{ $r['full_name'] }}</td>
                        <td style="font-family:var(--mono);">{{ $r['whatsapp_number'] }}</td>
                        <td>{{ $r['status'] }}</td>
                        <td style="font-family:var(--mono);">{{ $r['region_id'] ?: '—' }}</td>
                        <td style="font-family:var(--mono);">{{ $r['district_id'] ?: '—' }}</td>
                        <td style="font-family:var(--mono);">{{ $r['edd_date'] ?: '—' }}</td>
                        <td style="font-family:var(--mono);">{{ $r['baby_age'] !== '' ? $r['baby_age'] : '—' }}</td>
                        <td style="color:#b91c1c;">{{ !empty($r['_errors']) ? implode(', ', $r['_errors']) : '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
