@extends('layouts.admin')

@section('title', 'Journals')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Journals</h3>
        <p>Browse posted journals and drill down into journal lines.</p>
    </div>
</div>

<div class="content-card" style="padding: 16px;">
    <form method="GET" action="{{ route('admin.journals') }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px;">
        <input name="q" value="{{ request('q') }}" placeholder="Search ref/description" style="flex:1; min-width:220px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <input type="date" name="from" value="{{ request('from') }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <input type="date" name="to" value="{{ request('to') }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <input name="type" value="{{ request('type') }}" placeholder="journal_type" style="min-width:160px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <button class="btn-primary" type="submit">Filter</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.journals') }}">Reset</a>
    </form>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:140px;">Posting Date</th>
                    <th style="width:140px;">Ref</th>
                    <th>Description</th>
                    <th style="width:160px;">Type</th>
                    <th style="width:90px;">Status</th>
                    <th style="width:120px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($journals as $j)
                    <tr>
                        <td>{{ optional($j->posting_date)->toDateString() }}</td>
                        <td style="font-family: var(--mono); font-weight:700;">{{ $j->ref }}</td>
                        <td>{{ $j->description }}</td>
                        <td>{{ $j->journal_type }}</td>
                        <td>{{ $j->status }}</td>
                        <td style="text-align:right;">
                            <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.journals.show', $j) }}">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $journals->links() }}</div>
</div>
@endsection
