@extends('layouts.admin')
@section('title', 'Performance Reviews')
@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Performance Reviews</h3>
        <p>Track employee ratings and feedback.</p>
    </div>
    <div class="header-actions">
        <button class="btn-primary" onclick="document.getElementById('addReviewModal').style.display='flex'">Add Review</button>
    </div>
</div>
<div class="content-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Reviewer</th>
                    <th>Rating</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $r)
                <tr>
                    <td>{{ $r->employee->first_name }} {{ $r->employee->last_name }}</td>
                    <td>{{ $r->review_date->format('M d, Y') }}</td>
                    <td>{{ $r->reviewer_name }}</td>
                    <td>{{ $r->rating }}/5</td>
                    <td>{{ $r->comments }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:10px;">{{ $reviews->links() }}</div>
</div>

<div id="addReviewModal" class="modal-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:9999;">
    <div class="modal" style="background:#fff; padding:20px; border-radius:10px; width:400px;">
        <h3>Add Performance Review</h3>
        <form method="POST" action="{{ route('admin.hrm.performance.store') }}">
            @csrf
            <div style="margin-bottom:10px;">
                <label style="display:block;">Employee</label>
                <select name="employee_id" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;">
                    @foreach($employees as $e)
                        <option value="{{ $e->id }}">{{ $e->first_name }} {{ $e->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom:10px;">
                <label style="display:block;">Date</label>
                <input type="date" name="review_date" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;">
            </div>
            <div style="margin-bottom:10px;">
                <label style="display:block;">Reviewer Name</label>
                <input type="text" name="reviewer_name" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;">
            </div>
            <div style="margin-bottom:10px;">
                <label style="display:block;">Rating (1-5)</label>
                <input type="number" name="rating" min="1" max="5" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;">
            </div>
            <div style="margin-bottom:10px;">
                <label style="display:block;">Comments</label>
                <textarea name="comments" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;"></textarea>
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" onclick="document.getElementById('addReviewModal').style.display='none'" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Save Review</button>
            </div>
        </form>
    </div>
</div>
@endsection
