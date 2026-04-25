@extends('layouts.admin')
@section('title', 'Recruitment')
@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Job Openings</h3>
        <p>Manage recruitment and open positions.</p>
    </div>
    <div class="header-actions">
        <button class="btn-primary" onclick="document.getElementById('addJobModal').style.display='flex'">Add Job</button>
    </div>
</div>
<div class="content-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Date Posted</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jobs as $j)
                <tr>
                    <td>{{ $j->title }}</td>
                    <td>{{ $j->department }}</td>
                    <td><span class="badge {{ $j->status === 'open' ? 'badge-success' : 'badge-danger' }}">{{ strtoupper($j->status) }}</span></td>
                    <td>{{ $j->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:10px;">{{ $jobs->links() }}</div>
</div>

<div id="addJobModal" class="modal-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:9999;">
    <div class="modal" style="background:#fff; padding:20px; border-radius:10px; width:400px;">
        <h3>Add Job Opening</h3>
        <form method="POST" action="{{ route('admin.hrm.recruitment.store') }}">
            @csrf
            <div style="margin-bottom:10px;">
                <label style="display:block;">Job Title</label>
                <input type="text" name="title" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;">
            </div>
            <div style="margin-bottom:10px;">
                <label style="display:block;">Department</label>
                <input type="text" name="department" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;">
            </div>
            <div style="margin-bottom:10px;">
                <label style="display:block;">Description</label>
                <textarea name="description" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;"></textarea>
            </div>
            <div style="margin-bottom:10px;">
                <label style="display:block;">Status</label>
                <select name="status" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;">
                    <option value="open">Open</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" onclick="document.getElementById('addJobModal').style.display='none'" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Save Job</button>
            </div>
        </form>
    </div>
</div>
@endsection
