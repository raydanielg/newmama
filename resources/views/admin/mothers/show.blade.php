@extends('layouts.admin')

@section('title', 'Mother Details')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $mother->full_name }}</h3>
        <p>Joined {{ $mother->created_at->diffForHumans() }}</p>
    </div>
    <div class="header-actions" style="display:flex; gap:10px;">
        <a href="{{ route('admin.mothers') }}" class="btn-primary" style="text-decoration:none;">Back to List</a>
        <a class="btn-icon" href="{{ route('admin.mothers.edit', $mother) }}">Edit</a>
        <a class="btn-icon" href="{{ route('admin.mothers.messages', $mother) }}">Messages</a>
        <form method="POST" action="{{ route('admin.mothers.destroy', $mother) }}" style="display:inline;" onsubmit="return confirm('Delete this mother record?');">
            @csrf
            @method('DELETE')
            <button class="btn-icon" type="submit">Delete</button>
        </form>
    </div>
</div>

@if(session('status'))
    <div class="content-card" style="padding:12px; border:1px solid #bbf7d0; background:#f0fdf4; color:#166534; margin-bottom:14px;">{{ session('status') }}</div>
@endif

<div class="content-card">
    <div class="table-responsive">
        <table class="admin-table">
            <tbody>
                <tr>
                    <th style="width: 220px;">Full Name</th>
                    <td>{{ $mother->full_name }}</td>
                </tr>
                <tr>
                    <th>WhatsApp Number</th>
                    <td>{{ $mother->whatsapp_number }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ ucfirst(str_replace('_', ' ', $mother->status)) }}</td>
                </tr>
                <tr>
                    <th>Country</th>
                    <td>{{ $mother->country->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Region</th>
                    <td>{{ $mother->region->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>District</th>
                    <td>{{ $mother->district->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>EDD Date</th>
                    <td>{{ $mother->edd_date ? $mother->edd_date->format('M d, Y') : '-' }}</td>
                </tr>
                @if($mother->status === 'pregnant' && $mother->weeks_pregnant !== null)
                <tr>
                    <th>Pregnancy Progress</th>
                    <td>
                        <div style="font-weight:800; color:#2563eb;">Wiki ya {{ $mother->weeks_pregnant }} (Trimester {{ $mother->trimester }})</div>
                        <div style="width:100%; height:8px; background:#e5e7eb; border-radius:4px; margin-top:8px; overflow:hidden;">
                            <div style="width:{{ ($mother->weeks_pregnant / 40) * 100 }}%; height:100%; background:#2563eb;"></div>
                        </div>
                    </td>
                </tr>
                @endif
                <tr>
                    <th>Current Step</th>
                    <td>
                        <span class="badge" style="background:#f3f4f6; color:#111827; font-weight:800;">{{ $mother->current_step_label }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Baby Age (Months)</th>
                    <td>{{ $mother->baby_age ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Trying Duration</th>
                    <td>{{ $mother->trying_duration ? ucfirst(str_replace('_', ' ', $mother->trying_duration)) : '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
