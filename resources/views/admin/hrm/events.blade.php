@extends('layouts.admin')
@section('title', 'Events')
@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>HRM Events</h3>
        <p>Track company events and meetings.</p>
    </div>
    <div class="header-actions">
        <button class="btn-primary" onclick="document.getElementById('addEventModal').style.display='flex'">Add Event</button>
    </div>
</div>
<div class="content-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $e)
                <tr>
                    <td>{{ $e->title }}</td>
                    <td>{{ $e->event_date->format('M d, Y') }}</td>
                    <td>{{ $e->location }}</td>
                    <td>{{ $e->description }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:10px;">{{ $events->links() }}</div>
</div>

<div id="addEventModal" class="modal-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:9999;">
    <div class="modal" style="background:#fff; padding:20px; border-radius:10px; width:400px;">
        <h3>Add Event</h3>
        <form method="POST" action="{{ route('admin.hrm.events.store') }}">
            @csrf
            <div style="margin-bottom:10px;">
                <label style="display:block;">Event Title</label>
                <input type="text" name="title" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;">
            </div>
            <div style="margin-bottom:10px;">
                <label style="display:block;">Date</label>
                <input type="date" name="event_date" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;">
            </div>
            <div style="margin-bottom:10px;">
                <label style="display:block;">Location</label>
                <input type="text" name="location" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;">
            </div>
            <div style="margin-bottom:10px;">
                <label style="display:block;">Description</label>
                <textarea name="description" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;"></textarea>
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" onclick="document.getElementById('addEventModal').style.display='none'" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Save Event</button>
            </div>
        </form>
    </div>
</div>
@endsection
