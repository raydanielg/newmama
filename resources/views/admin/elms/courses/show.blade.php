@extends('layouts.admin')

@section('title', $course->title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $course->title }}</h3>
        <p>{{ $course->code }} · {{ $course->category ?: 'No category' }} · {{ $course->level ?: 'No level' }}</p>
    </div>
    <div class="header-actions" style="display:flex; gap:10px;">
        <a href="{{ route('admin.elms.courses') }}" class="btn-primary" style="text-decoration:none;">Back</a>
        <a href="{{ route('admin.elms.courses.edit', $course) }}" class="btn-icon" style="text-decoration:none;">Edit</a>
        <form method="POST" action="{{ route('admin.elms.courses.toggle-status', $course) }}" style="display:inline;">
            @csrf
            <button class="btn-icon" type="submit">{{ $course->is_active ? 'Deactivate' : 'Activate' }}</button>
        </form>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <div style="display:grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap:12px;">
        <div>
            <div style="font-size:12px; color:#6b7280;">Status</div>
            <div><span class="badge {{ $course->is_active ? 'status-trying' : 'status-pregnant' }}">{{ $course->is_active ? 'ACTIVE' : 'INACTIVE' }}</span></div>
        </div>
        <div>
            <div style="font-size:12px; color:#6b7280;">Duration</div>
            <div style="font-weight:800;">{{ $course->duration_hours ? $course->duration_hours . ' hrs' : '—' }}</div>
        </div>
        <div>
            <div style="font-size:12px; color:#6b7280;">Base Price</div>
            <div style="font-weight:900;">{{ $course->currency }} {{ number_format((float) $course->base_price, 2) }}</div>
        </div>
        <div>
            <div style="font-size:12px; color:#6b7280;">Grand Total</div>
            <div style="font-weight:900;">{{ $course->currency }} {{ number_format((float) $grandTotal, 2) }}</div>
            <div style="font-size:12px; color:#6b7280;">Fees: {{ number_format((float) $feesTotal, 2) }}</div>
        </div>
    </div>

    @if($course->description)
        <div style="margin-top:14px;">
            <div style="font-size:12px; color:#6b7280; font-weight:800; margin-bottom:6px;">Description</div>
            <div style="white-space:pre-wrap;">{{ $course->description }}</div>
        </div>
    @endif
</div>

<div style="display:grid; grid-template-columns: 1fr; gap:14px;">
    <div class="content-card" style="padding:16px;">
        <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>Additional Prices / Fees</h3></div>

        <form method="POST" action="{{ route('admin.elms.courses.fees.store', $course) }}" style="display:grid; grid-template-columns: 1.3fr 0.6fr 0.4fr 0.5fr 0.5fr; gap:10px; align-items:end; margin-bottom:14px;">
            @csrf
            <div>
                <label style="display:block; font-weight:700; margin-bottom:6px;">Fee Name</label>
                <input name="name" value="{{ old('name') }}" placeholder="e.g. Exam Fee" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('name')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>
            <div>
                <label style="display:block; font-weight:700; margin-bottom:6px;">Amount</label>
                <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount', 0) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('amount')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>
            <div>
                <label style="display:block; font-weight:700; margin-bottom:6px;">Currency</label>
                <select name="currency" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    @foreach(['TZS','USD'] as $cc)
                        <option value="{{ $cc }}" {{ old('currency', $course->currency) === $cc ? 'selected' : '' }}>{{ $cc }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block; font-weight:700; margin-bottom:6px;">Required</label>
                <select name="is_required" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    <option value="1" {{ old('is_required', '1') === '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('is_required') === '0' ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div>
                <label style="display:block; font-weight:700; margin-bottom:6px;">Sort</label>
                <input type="number" min="0" name="sort_order" value="{{ old('sort_order', 0) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>
            <div style="grid-column: 1 / -1;">
                <button class="btn-primary" type="submit">Add Fee</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Fee</th>
                        <th style="width:140px;">Flags</th>
                        <th class="td-right" style="width:180px;">Amount</th>
                        <th style="width:240px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($course->fees as $fee)
                        <tr>
                            <td>
                                <div style="font-weight:800;">{{ $fee->name }}</div>
                                <div style="font-size:12px; color:#6b7280;">Sort: {{ $fee->sort_order }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $fee->is_active ? 'status-trying' : 'status-pregnant' }}">{{ $fee->is_active ? 'ACTIVE' : 'INACTIVE' }}</span>
                                <span class="badge {{ $fee->is_required ? 'status-trying' : 'status-pregnant' }}">{{ $fee->is_required ? 'REQ' : 'OPT' }}</span>
                            </td>
                            <td class="td-right" style="font-weight:900; font-family:var(--mono);">{{ $fee->currency }} {{ number_format((float) $fee->amount, 2) }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.elms.courses.fees.toggle', [$course, $fee]) }}" style="display:inline;">
                                    @csrf
                                    <button class="btn-icon" type="submit">{{ $fee->is_active ? 'Deactivate' : 'Activate' }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.elms.courses.fees.delete', [$course, $fee]) }}" style="display:inline;" onsubmit="return confirm('Remove this fee?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-icon" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center; padding:18px; color:#6b7280;">No additional prices yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:12px; font-size:12px; color:#6b7280;">
            Base: <strong>{{ $course->currency }} {{ number_format((float) $course->base_price, 2) }}</strong>
            &nbsp;|&nbsp; Fees Total: <strong>{{ $course->currency }} {{ number_format((float) $feesTotal, 2) }}</strong>
            &nbsp;|&nbsp; Grand Total: <strong>{{ $course->currency }} {{ number_format((float) $grandTotal, 2) }}</strong>
        </div>
    </div>
</div>
@endsection
